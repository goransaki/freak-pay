/// <reference path="jquery.ts"/>
var SearchToggle = (function () {
    function SearchToggle(showButton, hideButton, searchContainer) {
        this.showButton = $(showButton);
        this.hideButton = $(hideButton);
        this.search = $(searchContainer);
        this.bindEvents();
    }
    /**
     * Bind events to the elements
     */
    SearchToggle.prototype.bindEvents = function () {
        this.bindShowButtonEvent();
        this.bindHideButtonEvent();
    };
    /**
     * Bind show button event
     */
    SearchToggle.prototype.bindShowButtonEvent = function () {
        var _this = this;
        this.showButton.click(function () { return _this.showSearch(); });
    };
    /**
     * Bind hide button event
     */
    SearchToggle.prototype.bindHideButtonEvent = function () {
        var _this = this;
        this.hideButton.click(function () { return _this.hideSearch(); });
    };
    /**
     * Show search panel, hide 'show search' button and show 'hide search' button
     */
    SearchToggle.prototype.showSearch = function () {
        var _this = this;
        this.search.slideDown();
        this.showButton.fadeOut(function () { return _this.fadeInElement(_this.hideButton); });
    };
    /**
     * Hide search panel, hide 'hide search' button and show 'show search' button
     */
    SearchToggle.prototype.hideSearch = function () {
        var _this = this;
        this.search.slideUp();
        this.hideButton.fadeOut(function () { return _this.fadeInElement(_this.showButton); });
    };
    /**
     * Fade in element as inline-block
     * @param element
     */
    SearchToggle.prototype.fadeInElement = function (element) {
        // it would sometimes set the button to display:inline which would make the page jumpy
        element.fadeIn().css("display", "inline-block");
    };
    return SearchToggle;
})();
//# sourceMappingURL=SearchToggle.js.map