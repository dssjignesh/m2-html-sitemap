define([
    'jquery',
    'Dss_SeoCore/js/model/mess'
], function ($, mess) {
    'use strict';

    var mixin = {
        /**
         * @inheritDoc
         */
        onDataSaved: function (data) {
            if (!data.error) {
                mess.hasMess(true);
                mess.mess(data.messages[0]);
            } else {
                mess.hasMess(undefined);
                mess.mess(undefined);
            }
            return this._super();
        }
    }

    return function (editorComponent) {
        return editorComponent.extend(mixin);
    }
});
