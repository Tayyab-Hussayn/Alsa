/*!
 * ================== admin/assets/js/plugin-manager.js ===================
 **/

(function($, window, document, undefined) {
	"use strict";

    // plugin constructor
    function Plugin(element) {
        this.element = element;
        this.$element = $(element);

        this._ajaxData = null;
        this._ajaxUrl = window.vamtam_setup_params.ajaxurl;

        this.init();
	}

	$( document ).ready( function () {
		var ip_btn = $( 'a#install-plugins-btn' );
		if ( ip_btn.length > 0 ) {
			new Plugin();
		}
	});

    // Avoid Plugin.prototype conflicts
    $.extend(Plugin.prototype, {
        init: function() {

            // Install Plugins Event
            $(document).on(
                "click",
                ".install-plugins",
                this._pluginManager.bind(this)
            );
        },

        /**
         * global AJAX callback
         */
        _globalAJAX: function(callback) {
            // Do Ajax & update default value
            $.ajax({
                url: this._ajaxUrl,
                type: "post",
                data: this._ajaxData
            }).done(callback);
        },

        /* ------------------------------------------------------------------------------ */
        // Plugin Manager

        /**
         * Install/Activate Plugin
         */
        _pluginManager: function(e) {
            // Check currentTarget existence
            if (!e.currentTarget) {
                return;
            }
            // check preventDefault existence
            if (typeof e.preventDefault !== "undefined") {
                e.preventDefault();
            }
            // Set variable
            var $buttonElement = $(e.currentTarget);
            this.$buttonParentEl = $buttonElement.closest(
                ".vamtam-has-required-plugins"
            );
            this.$pluginsListEl = this.$buttonParentEl.find(
                ".vamtam-plugins"
            );
            this._selectedPluginsNum = this.$buttonParentEl.find(
                '.vamtam-plugin input[name="plugin[]"]:checked'
            ).length;
            this._itemsCompleted = 0;
            this._attemptsBuffer = 0;
            this._currentItem = null;
            this._buttonTarget = e.currentTarget;
            this.$currentNode = null;

            // Manipulation
            this.$pluginsListEl.addClass("installing");
            $buttonElement
                .text(window.vamtam_setup_params.btnworks_text)
                .addClass("disabled");

            this._processPlugins();
        },

        /**
         * Process selected plugins
         */
        _processPlugins: function() {
            var self = this,
                doNext = false,
                $pluginsList = this.$buttonParentEl.find(".vamtam-plugin");

            if (this.$currentNode) {
                if (!this.$currentNode.data("done_item")) {
                    this._itemsCompleted++;
                    this.$currentNode.data("done_item", 1);
                }
                this.$currentNode.find(".spinner").css("visibility", "hidden");
            }

            $pluginsList.each(function() {
                if (self._currentItem == null || doNext) {

                    if (
                        $(this)
                            .find('input[name="plugin[]"]')
                            .is(":checked")
                    ) {
                        $(this).addClass("work-in-progress");
                        self._currentItem = $(this).data("slug");
                        self.$currentNode = $(this);
                        self.$currentNode
                            .find(".spinner")
							.css("visibility", "visible");

                        self._installPlugin();
                        doNext = false;
                    }
                } else if ($(this).data("slug") === self._currentItem) {
                    $(this).removeClass("work-in-progress");
                    doNext = true;
                }
            });

            // If all plugins finished, then
            if (this._itemsCompleted >= this._selectedPluginsNum) {
                // Remove installing class
                this.$buttonParentEl
                    .find(".vamtam-plugins")
                    .removeClass("installing");
                // Remove disable class from button
                this.$buttonParentEl
                    .find("#install-plugins-btn")
                    .removeClass("disabled");
                $(this._buttonTarget).text(window.vamtam_setup_params.activate_text);
                // Change the text of "Skip This Step" button to "Next Step"
                this.$buttonParentEl
                    .find(".skip-next")
                    .text(window.vamtam_setup_params.nextstep_text);

            }
        },

        /**
         * Process plugin by slug
         */
        _installPlugin: function() {
            if (this._currentItem) {
                var plugins = this.$buttonParentEl
                    .find('.vamtam-plugins input[name="plugin[]"]:checked')
                    .map(function() {
                        return $(this).val();
                    })
                    .get();
                this._ajaxData = {
                    action: "vamtam_setup_plugins",
                    wpnonce: window.vamtam_setup_params.wpnonce,
                    slug: this._currentItem,
                    plugins: plugins
                };
                this._globalAJAX(
                    function(response) {
                        this._pluginActions(response);
                    }.bind(this)
                );
            }
        },

        /**
         * Plugin activation events
         */
        _pluginActions: function(response) {
            // Check response type
            if (typeof response === "object" && response.message !== undefined ) {
                // Update plugin status message
                this.$currentNode
                    .find(".column-status span")
                    .text(response.message);
                // At this point, if the response contains the url, it means that we need to install/activate it.
                if (typeof response.url !== "undefined") {
                    if (this.currentItemHash === response.hash) {
                        this.$currentNode
                            .data("done_item", 0)
                            .find(".column-status span")
                            .text("failed");
                        // If there is an error, we will try to reinstall plugin wice with buffer checkup.
                        if (this._attemptsBuffer > 1) {
                            // Reset buffer value
                            this._attemptsBuffer = 0;
                            // error & try again with next plugin
                            this.$currentNode
                                .addClass("vamtam-error")
                                .find(".column-status span")
                                .text("Ajax Error!");
                            this._processPlugins();
                        } else {
                            // Try again & update buffer value
                            this.currentItemHash = null;
                            this._attemptsBuffer++;
                            this._installPlugin();
                        }
                    } else {
                        // we have an ajax url action to perform.
                        this._ajaxUrl = response.url;
                        this._ajaxData = response;
                        this.currentItemHash = response.hash;
                        this._globalAJAX(
                            function() {
                                // Reset ajax url to default admin ajax value
                                this._ajaxUrl = window.vamtam_setup_params.ajaxurl;
                                this.$currentNode
                                    .find(".column-status span")
                                    .text(response.message);
                                this._installPlugin();
                            }.bind(this)
                        );
                    }
                } else {
                    // otherwise it's just installed and we should make a notify to user
                    this.$currentNode
                        .addClass("vamtam-success")
                        .find(".vamtam-check-column")
                        .remove();
                    this.$currentNode
                        .find(".check-column")
                        .append(
                            "<i class='vamtam-success-icon vamtam-check-mark'></i>"
                        );
                    // Then jump to next plugin
                    this._processPlugins();
                }
            }
        },
    });
})(jQuery, window, document);