define([
    'jquery',
    'ko',
    'Dss_SeoCore/js/model/mess'
],function ($, ko, mess) {
    'use strict';

    var mixin = {
        templateNotice: 'Dss_SeoCore/grid/listing',

        /**
         * @inheritDoc
         */
        initialize: function () {
            var self = this;
            mess.hasMess.subscribe(function (hasMess) {
                self.hasBreadcrumbsWarning(hasMess);
            }.bind(this));
            mess.mess.subscribe(function (mess) {
                self.breadcrumbsWarning(mess);
            }.bind(this));
            return this._super();
        },
        /**
         * @inheritDoc
         */
        initObservable: function () {
            this._super().observe([
                'hasBreadcrumbsWarning',
                'breadcrumbsWarning'
            ]);

            return this;
        },
        /**
         * @inheritDoc
         */
        getTemplate: function () {
            console.log(this._super());
            return this.templateNotice !== undefined ? this.templateNotice : this._super();
        },
        /**
         * @inheritDoc
         */
        dismissAll: function () {
            this.hasBreadcrumbsWarning = false;
            $('.dss-message-system').remove();
            return this._super();
        }
    }

    return function (listingComponent) {
        return listingComponent.extend(mixin);
    }
});
