/// <reference path="jquery.ts"/>

class SearchToggle {
    showButton:JQuery;
    hideButton:JQuery;
    search:JQuery;

    constructor(showButton:string, hideButton:string, searchContainer:string) {
        this.showButton = $(showButton);
        this.hideButton = $(hideButton);
        this.search = $(searchContainer);

        this.bindEvents();
    }

    /**
     * Bind events to the elements
     */
    private bindEvents():void {
        this.bindShowButtonEvent();
        this.bindHideButtonEvent();
    }

    /**
     * Bind show button event
     */
    private bindShowButtonEvent():void {
        this.showButton.click(() => this.showSearch());
    }

    /**
     * Bind hide button event
     */
    private bindHideButtonEvent():void {
        this.hideButton.click(() => this.hideSearch());
    }

    /**
     * Show search panel, hide 'show search' button and show 'hide search' button
     */
    private showSearch():void {
        this.search.slideDown();
        this.showButton.fadeOut(() => this.fadeInElement(this.hideButton));
    }

    /**
     * Hide search panel, hide 'hide search' button and show 'show search' button
     */
    private hideSearch():void {
        this.search.slideUp();
        this.hideButton.fadeOut(() => this.fadeInElement(this.showButton));
    }

    /**
     * Fade in element as inline-block
     * @param element
     */
    private fadeInElement(element:JQuery):void {
        // it would sometimes set the button to display:inline which would make the page jumpy
        element.fadeIn().css("display", "inline-block");
    }
}