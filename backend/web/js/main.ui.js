/* 
 * @author nikola.radovic <dzona065@gmail.com>
 * 
 * Date: 3/25/14
 * Time: 07:48 PM
 */
var main = {}; // namespace
var modalID = 'main-modal';
var summaryID = 'active-form-summary';
var staticSummaryID = 'static-summary-alert';

$ = jQuery.noConflict();

$(function () {

    main.ui.init();

});

/**
 * main namespace for helper declarative methods
 */

main.ui = (function ($) {

    return {
        cachedTemplates: {},
        modal: null,
        hiddenLoadingIcon: '<i class="fa fa-spinner fa-spin hidden"></i> ',
        buttonLoadingText: '<i class="fa fa-spinner fa-spin"></i> Loading...',
        buttonSavingText: '<i class="fa fa-spinner fa-spin"></i> Saving...',
        defaultConfirmMessage: 'Do you wish to delete this item?',
        messageDuration: 6000,
        /**
         * Pushes flash messages
         * @param msg
         * @param type
         * @param duration
         */
        flashMessage: function (msg, type, duration) {
            var cssClass = '',
                closeBtn = '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>';
            type = type || 'info';
            duration = duration || main.ui.messageDuration;
            switch (type) {
                case 'info':
                    cssClass = 'alert-info';
                    break;
                case 'warning':
                    cssClass = 'alert-warning';
                    break;
                case 'error':
                    duration = 0;
                    cssClass = 'alert-danger';
                    break;
                case 'success':
                    cssClass = 'alert-success';
                    break;
            }
            $('#' + summaryID)
                .attr('class', '')
                .addClass('alert ' + cssClass)
                .html(closeBtn + msg)
                .show();
            if (duration) {
                setTimeout(function () {
                    $('#' + summaryID).fadeOut();
                }, duration);
            }
            main.ui.scrollToFlashMessage();
        },
        notify: function (title, type, message, position) {

            type = type || 'error';
            position = position || 'top right';

            $.Notification.notify(type, position, title, message);

            return this;
        },
        /**
         * array of multiple notifiers
         * @param data
         */
        multiple_notify: function (data) {
            $.each(data, function () {
                main.ui.notify(this.message, this.type);
            });
            return this;
        },
        controlDropDownPopulate: function () {
            var self = $(this),
                dest = $(self.data('destination'));

            self.attr('disabled', true);

            $.ajax({
                url: self.data('url'),
                type: 'post',
                data: {id: self.val()},
                dataType: 'html',
                success: function (data) {
                    dest.html(data);
                    self.attr('disabled', false);
                },
                error: function (XHR) {
                    main.ui.notify('Ooops, something went wrong populating dropdown...', 'error');
                    self.attr('disabled', false);
                }
            });

        },
        /**
         * created to attach url redirection functionality to certain buttons
         */
        controlUrl: function () {
            var that = $(this);
            if (that.hasClass('disabled')) {
                return false;
            }
            var url = that.prop('href') || that.attr('data-href');
            if (!url) {
                return false;
            }
            var confirm = that.attr('data-confirm');
            if (confirm) {
                main.ui.confirm(confirm, function (response) {
                    if (response) {
                        document.location.href = url;
                    }
                    return response;
                });
            }
            document.location.href = url;
            return true;
        },
        /**
         * Handles a form submission via ajax call through meta-data and updates views (grids | lists) if specified
         * @return {*}
         */
        controlAjaxSubmit: function (e) {
            e.preventDefault();
            var self = $(this);
            if (self.hasClass('disabled')) {
                return false;
            }
            var frm = this.form ? $(this.form) : self.data('form-id') ? $('#' + self.data('form-id')) : self.closest('form')[0];
            $.ajax({
                url: frm.attr('action'),
                type: 'post',
                data: frm.serialize(),
                dataType: 'json',
                success: function (data) {
                    if (data.success) {
                        main.ui.modal.modal('hide');
                    } else {
                        main.yiiactiveform.updateInputs(frm, data.errors || []);
                    }
                    main.ui.notify(data.message, data.success ? 'success' : 'error');
                    $(document).trigger('form-submitted', [data, self, frm]);
                },
                error: function (XHR) {
                    main.ui.modal.find('.modal-content').html(XHR.responseText);
                }
            });
            return false;
        },
        /**
         * Handles a form submission via ajax call through meta-data and updates views (grids | lists) if specified
         * @return {*}
         */
        controlPjaxAction: function () {
            var self = $(this), data = {},
                gridId = self.data('grid'),
                confirmMsg = self.data('confirm-msg') || false,
                type = self.data('type') || 'get',
                url = self.prop('href') || self.attr('data-href');

            if (self.hasClass('disabled') || !url) {
                return false;
            }

            $.each(self.data(), function (key, value) {
                data[key] = value;
            });

            var sendRequest = function (isOk) {
                if (!isOk) {
                    return false;
                }

                $.ajax({
                    url: url,
                    type: type,
                    data: data,
                    dataType: 'json',
                    success: function (data) {
                        if (data.success) {
                            $.pjax.reload({container: '#' + gridId, timeout: 5000});
                        }
                        main.ui.notify(data.message, data.success ? 'success' : 'error');
                    },
                    error: function (XHR) {
                        main.ui.notify(XHR.responseText, 'error');
                    }
                });
            };

            if (confirmMsg) {
                main.ui.confirm(confirmMsg, sendRequest);
                return false;
            }

            sendRequest(true);

            return false;
        },
        loading: function (state) {
            if (state) {
                $('#main-loading-overlay').show();
            } else {
                $('#main-loading-overlay').hide();
            }
        },
        /**
         * displays a modal witha url
         */
        modalControl: function (e) {
            e.preventDefault();
            var self = $(this);
            if (!self.data('loading-text')) {
                self.data('loading-text', main.ui.buttonLoadingText);
            }
            if (self.hasClass('disabled')) {
                return false;
            }
            var url = self.attr('data-href') || self.attr('href');
            var data = self.data('params') || '';
            var options = {backdrop: 'static'};
            if (self.attr('data-height')) {
                options.height = self.attr('data-height');
                if (main.ui.modal.data('modal')) {
                    main.ui.modal.data('modal').options.height = self.attr('data-height');
                }
            }
            if (self.attr('data-width')) {
                options.width = self.attr('data-width');
                if (main.ui.modal.data('modal')) {
                    main.ui.modal.data('modal').options.width = self.attr('data-width');
                }
            }

            main.ui.modal.find('.modal-content').attr('class', 'modal-content');
            if (self.attr('data-content-class')) {
                main.ui.modal.find('.modal-content').addClass(self.attr('data-content-class'));
            }

            main.ui.loading(true);

            setTimeout(function () {
                var modalContent = main.ui.modal.find('.modal-content');
                modalContent.load(url, data, function () {
                    main.ui.loading(false);
                    main.ui.modal.modal(options);
                });
            }, 200);
            return false;
        },
        openModal: function (url) {
            this.modalControl.call($('<a href="' + url + '">'), {preventDefault: $.noop});
        },
        /**
         * closes a modal
         */
        modalClose: function () {
            main.ui.modal.modal('hide');
        },
        /**
         * submits a form within a modal
         * @return {Boolean}
         */
        modalControlSubmit: function (e) {
            e.preventDefault();
            var self = $(this);

            if (self.hasClass('disabled')) {
                return false;
            }
            var frm = this.form ? $(this.form) : self.data('form-id') ? $('#' + self.data('form-id')) : self.closest('.modal-content').find('form');

            var closeModal = self.data('close-modal');

            if (closeModal) {
                main.ui.loading(true);
            }

            $.ajax({
                url: frm.attr('action'),
                type: 'post',
                data: frm.serialize(),
                dataType: 'json',
                success: function (data) {
                    if (!data.success) {
                        main.yiiactiveform.updateInputs(frm, data.errors || []);

                    } else if (!closeModal) {
                        main.ui.modal.modal('hide');
                    } else {
                        main.ui.loading(false);
                    }

                    main.ui.notify(data.message, data.success ? 'success' : 'error');

                    $(document).trigger('modal-submitted', [data, self, frm]);
                },
                error: function (XHR) {
                    main.ui.modal.find('.modal-content').html(XHR.responseText);
                }
            });
            return false;
        },
        /**
         * Submits a form. Form id is declarative specified on the button  "data-form". This way, we can
         * place the submit button wherever we wish.
         */
        controlSubmit: function (e) {
            var self = $(this);
            if (!self.data('loading-text')) {
                self.data('loading-text', main.ui.buttonSavingText);
            }
            if (self.hasClass('disabled')) {
                return false;
            }
            var frm = this.form ? $(this.form) : self.data('form') ? $('#' + self.data('form')) : self.closest('form'),
                error = frm.find('.has-error:visible:first');

            if (frm && frm.length) {
                if (self.attr('type') !== 'submit') {
                    if (error.length) {
                        main.ui.flashMessage('Please fix errors before submitting.', 'error');
                        return false;
                    }
                    frm.submit();
                } else {
                }
                return true;
            }
            main.ui.notify('Unable to get a form reference!', 'error');
            return false;
        },
        /**
         * Displays confirmation alert box before proceeding with adding items to work bag
         */
        controlConfirm: function (e) {
            var self = $(this);
            if (self.hasClass('disabled')) {
                return false;
            }
            var msg = self.attr('data-msg') || main.ui.defaultConfirmMessage;
            var url = self.prop('href') || self.attr('data-url');
            main.ui.confirm(msg, function (result) {
                if (url && result) {
                    var isJsonResponse = (self.data('json-response') == 1);

                    main.ui.loading(true);

                    if (isJsonResponse) {
                        var method = self.data('method') || 'POST';
                        var pjaxOptions = {
                            container: '#' + self.data('pjax-id'),
                            url: self.data('pjax-url')
                        };

                        ajaxRequest(pjaxOptions, url, method);
                    } else {
                        document.location.href = url;
                    }

                    return true;
                }
            });
            return false;
        },
        /**
         * toggle element visibility
         */
        controlDisplayToggles: function () {
            var self = $(this);
            $('.' + $(this).attr('data-toggle-display')).toggle();

            if (self.attr('data-callback')) {
                executeFunctionByName(self.attr('data-callback'), window);
            }
        },
        /**
         * toggle elements with checkbox
         */
        controlCheckboxToggles: function () {
            var self = $(this),
                $el = $('.' + $(this).attr('data-toggle'));

            var checked = $(this).attr('data-invert') ? !this.checked : this.checked;
            checked ? $el.fadeIn() : $el.fadeOut();

            if (self.attr('data-callback')) {
                executeFunctionByName(self.attr('data-callback'), window);
            }
        },
        /**
         * toggle elements with dropdown
         */
        controlDropdownToggles: function () {
            var self = $(this),
                $el = $('.' + self.attr('data-toggle'));

            if (self.find('option:selected').val() === self.attr('data-trigger-key')) {
                $el.fadeIn();
                if (self.attr('data-callback')) {
                    executeFunctionByName(self.attr('data-callback'), window);
                }
                return;
            }
            $el.fadeOut();
            if (self.attr('data-callback')) {
                executeFunctionByName(self.attr('data-callback'), window);
            }
        },
        /**
         * scroll to first error bow or form element with error
         */
        scrollToError: function () {
            var error = $('body').find('.alert-danger:visible:first');
            error = error.length ? error : $('body').find('.has-error:visible:first');
            if (error.length) {
                $('html, body').animate({
                    scrollTop: error.offset().top
                }, 500);
            }
        },
        /**
         * scroll to first alert box
         */
        scrollToFlashMessage: function () {
            var alert = $('body').find('.alert:visible:first');

            if (alert.length) {
                $('html, body').animate({
                    scrollTop: alert.offset().top
                }, 500);
            }
        },
        /**
         * Bootbox alert wrapper
         *
         * @param string message
         * @param function callback
         */
        alert: function (message, callback) {
            bootbox.alert(message, callback);
        },
        /**
         * SweetAlert confirm wrapper
         *
         * @param string message
         * @param function callback
         */
        confirm: function (message, callback, type, title) {
            type = type || 'warning';
            title = title || 'Are you sure?';

            swal({
                title: title,
                text: message,
                type: type,
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: true,
                closeOnCancel: true
            }, callback);
            //bootbox.confirm(message, callback);
        },
        /**
         *
         */
        pasteFromClipboard: function () {
            $($(this).data('target')).val(window.clipboardData.getData('Text'));
        },
        /**
         * Adds / remove spinner overlay
         * @param bool state
         */
        spin: function (state) {
            var spinnerId = 'loading_spinner',
                spinner = '<div id="' + spinnerId + '" class="spinner" style="display:none;"> <div class="spinner-container"><i class="fa fa-spinner fa-spin"></i></div></div>';
            if (state) {
                $('body').append(spinner);
                $('#' + spinnerId).show();
            } else {
                $('#' + spinnerId).remove();
            }
        },
        /**
         * Reload pjax and clear all filters
         */
        controlClearFormSubmit: function (e) {
            e.preventDefault();
            var self = $(this);
            var frm = this.form ? $(this.form) : self.data('form') ? $('#' + self.data('form')) : self.closest('form');
            $.pjax.reload({
                container: '#' + self.data('pjax-id'),
                url: frm.attr('action'),
                replace: true,
                timeout: 10000
            });
        },
        reloadContainers: function (e, data, btn, form) {
            var defaults = {timeout: 10000, push: false, reload: false};

            if (data.success) {
                $.each(main.ui._reloadData, function () {
                    var data = $.extend(defaults, this);
                    if ($(data.container).length) {
                        $.pjax.reload($.extend(defaults, this));
                    }
                });
            }
        },
        ajaxLoadingStart: function () {
            $('.item-ajax-update').addClass('ajax-loading');
        },
        ajaxLoadingFinished: function () {
            $('.item-ajax-update').removeClass('ajax-loading');
        },
        initButtonSpinners: function () {
            $(':submit').click(function (e) {
                $(this).addClass('submittedButton');
            }).prepend(main.ui.hiddenLoadingIcon);

            $('form').on('beforeSubmit', function (e) {
                var submitButton = $(".submittedButton:submit");
                var showSpinner = submitButton.data('no-spinner') == '1';

                if (e.result == false) {
                    submitButton.find('.fa').addClass("hidden").removeClass('submittedButton');
                    return false;
                }

                !showSpinner && submitButton.find('.fa').removeClass("hidden");

                return true;
            });

        },
        yiiConfirm: function (message, ok, cancel) {
            main.ui.confirm(message, function (result) {
                if (result) {
                    !ok || ok();
                    return;
                }

                !cancel || cancel();
            });
        },
        debounce: function (func, wait, immediate) {
            var timeout;
            return function () {
                var context = this, args = arguments;
                var later = function () {
                    timeout = null;
                    if (!immediate) func.apply(context, args);
                };
                var callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func.apply(context, args);
            };
        },
        /**
         * module init
         */
        init: function () {
            // get main.ui.modal layer
            main.ui.modal = $('#' + modalID);
            main.ui.modal.on('hidden.bs.modal', function () {
                main.ui.modal.find('.modal-content').empty();
            });

            $.fn.modal.Constructor.prototype.enforceFocus = function () {
            };

            main.ui.initButtonSpinners();

            // Remove static summary if present
            if ($('#' + staticSummaryID).is(':visible')) {
                setTimeout(function () {
                    $('#' + staticSummaryID).fadeOut();
                }, main.ui.messageDuration);
            }

            // declarative controls (to be modified in the future to yiiwheels and with a better approach)
            $(document)
                .on('click', '.btn-control-url', this.controlUrl)
                .on('click', '.btn-control-ajax-submit', this.controlAjaxSubmit)
                .on('click', '.btn-modal-control-close', this.modalClose)
                .on('click', '.btn-modal-control', this.modalControl)
                .on('click', '.btn-modal-control-submit', this.modalControlSubmit)
                .on('click', '.btn-control-submit', this.controlSubmit)
                .on('click', '.btn-control-pjax-action', this.controlPjaxAction)
                .on('click', '.btn-control-confirm', this.controlConfirm)
                .on('change', 'input:checkbox[data-toggle-display]', this.controlDisplayToggles)
                .on('change', 'select[data-toggle]', this.controlDropdownToggles)
                .on('change', 'input:checkbox[data-toggle]', this.controlCheckboxToggles)
                .on('change', '.dd-control-update', this.controlDropDownPopulate)
                .on('modal-submitted', this.reloadContainers)
                .bind('ajaxStart', this.ajaxLoadingStart)
                .bind('ajaxStop', this.ajaxLoadingFinished)
                .on('click', '.btn-control-clear', this.controlClearFormSubmit);

            yii.confirm = main.ui.yiiConfirm;
        }
    };
})(jQuery);

main.xhr = {
    isJsonResponse: function (xhr) {
        var ct = xhr.getResponseHeader("content-type") || "";

        if (ct.indexOf('json') > -1) {
            return true;
        }

        return false;
    },
    isHtmlResponse: function (xhr) {
        var ct = xhr.getResponseHeader("content-type") || "";

        if (ct.indexOf('html') > -1) {
            return true;
        }

        return false;
    }
};

main.yiiactiveform = (function ($) {
    return {
        submit: function ($form, $trigger) {
            var url = $form.attr('action');
            $.ajax({
                url: url,
                data: $form.serialize(),
                type: 'post',
                dataType: 'json',
                success: function (data) {
                    main.ui.flashMessage(data.message, data.type);
                    var reset = $trigger.attr('data-reset');
                    if (reset) {
                        $form[0].reset();
                    }
                    $trigger.button('reset');
                    if ($trigger.attr('data-callback')) {
                        executeFunctionByName($trigger.attr('data-callback'), window);
                    }
                },
                error: function (xhr) {
                    main.ui.flashMessage(xhr.responseText, 'error');
                    $trigger.button('reset');
                    var reset = $trigger.attr('data-reset');
                    if (reset) {
                        $form[0].reset();
                    }
                }
            });
        },
        validate: function ($form, $trigger) {
            var settings = $.fn.yiiactiveform.getSettings($form);
            if ($trigger.length === 0) {
                $trigger = $("div[class$='-submit'],div[class*='-submit ']");
            }
            if (!settings.validateOnSubmit) {
                return main.yiiactiveform.submit($form, $trigger);
            }
            settings.submitting = true;
            $.fn.yiiactiveform.validate($form, function (messages) {
                if ($.isEmptyObject(messages)) {
                    $.each(settings.attributes, function () {
                        $.fn.yiiactiveform.updateInput(this, messages, $form);
                    });
                    main.yiiactiveform.submit($form, $trigger);
                    return true;
                } else {
                    settings = $.fn.yiiactiveform.getSettings($form);
                    $.each(settings.attributes, function () {
                        $.fn.yiiactiveform.updateInput(this, messages, $form);
                    });
                    settings.submitting = false;
                    main.yiiactiveform.updateSummary($form, messages);
                    main.ui.scrollToError();
                    $trigger.button('reset');
                    return false;
                }
            });
        },
        updateSummary: function ($form, messages) {
            var settings = $.fn.yiiactiveform.getSettings($form),
                heading = '<p>Please fix the following input errors:</p>',
                list = '';

            $.each(settings.attributes, function () {
                if (messages && $.isArray(messages[this.id])) {
                    $.each(messages[this.id], function (j, message) {
                        list = list + '<li>' + message + '</li>';
                    });
                }
            });
            main.ui.flashMessage(heading + '<ul>' + list + '</ul>', 'error', 0);
        },
        addAttribute: function ($form, attribute) {
            var settings = $.fn.yiiactiveform.getSettings($form);
            settings.attributes.push(attribute);
            $form.data('settings', settings);
            /*
             * returns the value of the CActiveForm input field
             * performs additional checks to get proper values for checkbox / radiobutton / checkBoxList / radioButtonList
             * @param o object the jQuery object of the input element
             */
            var getAFValue = function (o) {
                var type;
                if (!o.length)
                    return undefined;
                if (o[0].tagName.toLowerCase() == 'span') {
                    var c = [];
                    o.find(':checked').each(function () {
                        c.push(this.value);
                    });
                    return c.join(',');
                }
                type = o.attr('type');
                if (type === 'checkbox' || type === 'radio') {
                    return o.filter(':checked').val();
                } else {
                    return o.val();
                }
            };
            var validate = function (attribute, forceValidate) {
                if (forceValidate) {
                    attribute.status = 2;
                }
                $.each(settings.attributes, function () {
                    if (this.value !== getAFValue($form.find('#' + this.inputID))) {
                        this.status = 2;
                        forceValidate = true;
                    }
                });
                if (!forceValidate) {
                    return;
                }

                if (settings.timer !== undefined) {
                    clearTimeout(settings.timer);
                }
                settings.timer = setTimeout(function () {
                    if (settings.submitting || $form.is(':hidden')) {
                        return;
                    }
                    if (attribute.beforeValidateAttribute === undefined || attribute.beforeValidateAttribute($form, attribute)) {
                        $.each(settings.attributes, function () {
                            if (this.status === 2) {
                                this.status = 3;
                                $.fn.yiiactiveform.getInputContainer(this, $form).addClass(this.validatingCssClass);
                            }
                        });
                        $.fn.yiiactiveform.validate($form, function (data) {
                            var hasError = false;
                            $.each(settings.attributes, function () {
                                if (this.status === 2 || this.status === 3) {
                                    hasError = $.fn.yiiactiveform.updateInput(this, data, $form) || hasError;
                                }
                            });
                            if (attribute.afterValidateAttribute !== undefined) {
                                attribute.afterValidateAttribute($form, attribute, data, hasError);
                            }
                        });
                    }
                }, attribute.validationDelay);
            };
            if (attribute.validateOnChange) {
                $form.find('#' + attribute.inputID).change(function () {
                    validate(attribute, false);
                }).blur(function () {
                    if (attribute.status !== 2 && attribute.status !== 3) {
                        validate(attribute, !attribute.status);
                    }
                });
            }
            if (attribute.validateOnType) {
                $form.find('#' + attribute.inputID).keyup(function () {
                    if (attribute.value !== getAFValue($(attribute))) {
                        validate(attribute, false);
                    }
                });
            }
        },
        createAttribute: function (model, id, name, options) {
            var defaults = {
                enableAjaxValidation: true,
                errorCssClass: "has-error",
                errorID: id + '_em_',
                hideErrorMessage: false,
                id: id,
                inputContainer: "div.form-group",
                inputID: id,
                model: model,
                name: name,
                status: 1,
                successCssClass: 'has-success',
                validateOnChange: true,
                validateOnType: false,
                validatingCssClass: 'validating',
                validationDelay: 200
            };
            return $.extend({}, defaults, options);
        },
        updateInputs: function ($form, messages) {
            var data = $form.data('yiiActiveForm');

            if (data === undefined) {
                return false;
            }

            $.each(data.attributes, function () {
                main.yiiactiveform.updateInput($form, this, messages);
            });
        },

        /**
         * Updates the error message and the input container for a particular attribute.
         * @param $form the form jQuery object
         * @param attribute object the configuration for a particular attribute.
         * @param messages array the validation error messages
         * @return boolean whether there is a validation error for the specified attribute
         */
        updateInput: function ($form, attribute, messages) {
            var data = $form.data('yiiActiveForm'),
                $input = main.yiiactiveform.findInput($form, attribute),
                hasError = false;

            if (!$.isArray(messages[attribute.id])) {
                messages[attribute.id] = [];
            }

            data.settings.successCssClass = ''; //disable successfully validated attributes highlight

            if ($input.length) {
                hasError = messages[attribute.id].length > 0;
                var $container = $form.find(attribute.container);
                var $error = $container.find(attribute.error);
                main.yiiactiveform.updateAriaInvalid($form, attribute, hasError);
                if (hasError) {
                    if (attribute.encodeError) {
                        $error.text(messages[attribute.id][0]);
                    } else {
                        $error.html(messages[attribute.id][0]);
                    }
                    $container.removeClass(data.settings.validatingCssClass + ' ' + data.settings.successCssClass).addClass(data.settings.errorCssClass);
                } else {
                    $error.empty();
                    $container.removeClass(data.settings.validatingCssClass + ' ' + data.settings.errorCssClass + ' ').addClass(data.settings.successCssClass);
                }
            }
            return hasError;
        }
        ,
        findInput: function ($form, attribute) {
            var $input = $form.find(attribute.input);

            if ($input.length && $input[0].tagName.toLowerCase() === 'div') {
                return $input.find('input');
            } else {
                return $input;
            }
        }
        ,
        updateAriaInvalid: function ($form, attribute, hasError) {
            if (attribute.updateAriaInvalid) {
                $form.find(attribute.input).attr('aria-invalid', hasError ? 'true' : 'false');
            }
        }
    }
        ;
})(jQuery);

function executeFunctionByName(functionName, context /*, args */) {
    var args = [].slice.call(arguments).splice(2);
    var namespaces = functionName.split(".");
    var func = namespaces.pop();
    for (var i = 0; i < namespaces.length; i++) {
        context = context[namespaces[i]];
    }
    return context[func].apply(this, args);
}

function ajaxRequest(pjaxOptions, url, method) {
    var options = $.extend({}, {push: false, timeout: 10000}, pjaxOptions);
    $.ajax({
        url: url,
        type: method || 'post',
        success: function (response) {
            if (options.container) {
                $.pjax.reload(options);
            }
            console.log(response);
            console.log(response.success ? 'success' : 'error');
            main.ui.notify(response.message, response.success ? 'success' : 'error');
            main.ui.loading(false);
        },
        error: function (error) {
            if (error.responseText) {
                main.ui.notify(error.responseText, 'error');
            }
            main.ui.loading(false);
        }
    });
}

main.template = (function () {
    var templateCache = {};


    return {
        cache: function (identifier, templateString) {
            templateCache[identifier] = _.template(templateString);
        },
        render: function (templateString, params) {
            var template = _.template(templateString);

            if (!params) {
                params = {};
            }

            return template(params);
        },
        renderCached: function (templateIdentifier, params) {
            if (!(templateIdentifier in templateCache)) {
                return '';
            }

            if (!params) {
                params = {};
            }

            return templateCache[templateIdentifier](params);
        }
    }
})();
$(document).ready(function () {
    $(document).on('pjax:send', function () {
        $(".loader-container").addClass("loader");
    });
    $(document).on('pjax:complete', function() {
        $(".loader-container").removeClass("loader");
    });
});
