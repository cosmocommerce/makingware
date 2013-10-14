/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    design
 * @package     base_default
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
if(typeof Product=='undefined') {
    var Product = {};
}
/**************************** BUNDLE PRODUCT **************************/
Product.Bundle = function(config){
    this.config = config;

    // Set preconfigured values for correct price base calculation
    if (config.defaultValues) {
        for (var option in config.defaultValues) {
            if (this.config['options'][option].isMulti) {
                var selected = new Array();
                for (var i = 0; i < config.defaultValues[option].length; i++) {
                    selected.push(config.defaultValues[option][i]);
                }
                this.config.selected[option] = selected;
            } else {
                this.config.selected[option] = new Array(config.defaultValues[option] + "");
            }
        }
    }

    this.reloadPrice();
};
Product.Bundle.prototype = {
    changeSelection: function(selection){
        var parts = selection.id.split('-');
        if (this.config['options'][parts[2]].isMulti) {
            selected = new Array();
            if (selection.tagName == 'SELECT') {
                for (var i = 0; i < selection.options.length; i++) {
                    if (selection.options[i].selected && selection.options[i].value != '') {
                        selected.push(selection.options[i].value);
                    }
                }
            } else if (selection.tagName == 'INPUT') {
                selector = parts[0]+'-'+parts[1]+'-'+parts[2];
                selections = $('.'+selector);
                for (var i = 0; i < selections.length; i++) {
                    if (selections[i].checked && selections[i].value != '') {
                        selected.push(selections[i].value);
                    }
                }
            }
            this.config.selected[parts[2]] = selected;
        } else {
            if (selection.value != '') {
                this.config.selected[parts[2]] = new Array(selection.value);
            } else {
                this.config.selected[parts[2]] = new Array();
            }
            this.populateQty(parts[2], selection.value);
        }
        this.reloadPrice();

    },

    reloadPrice: function() {
        var calculatedPrice = 0;
        for (var option in this.config.selected) {
            if (this.config.options[option]) {
                for (var i=0; i < this.config.selected[option].length; i++) {
                    var prices = this.selectionPrice(option, this.config.selected[option][i]);
                    calculatedPrice += Number(prices[0]);
                }
            }
        }

        optionsPrice.changePrice('bundle', calculatedPrice);
        optionsPrice.reload();

        return calculatedPrice;
    },

    selectionPrice: function(optionId, selectionId) {
        if (selectionId == '' || selectionId == 'none') {
            return 0;
        }
        var qty = null;
        if (this.config.options[optionId].selections[selectionId].customQty == 1 && !this.config['options'][optionId].isMulti) {
            if ($('#bundle-option-' + optionId + '-qty-input')[0]) {
                qty = $('#bundle-option-' + optionId + '-qty-input')[0].value;
            } else {
                qty = 1;
            }
        } else {
            qty = this.config.options[optionId].selections[selectionId].qty;
        }

        if (this.config.priceType == '0') {
            price = this.config.options[optionId].selections[selectionId].price;
            tierPrice = this.config.options[optionId].selections[selectionId].tierPrice;

            for (var i=0; i < tierPrice.length; i++) {
                if (Number(tierPrice[i].price_qty) <= qty && Number(tierPrice[i].price) <= price) {
                    price = tierPrice[i].price;
                }
            }
        } else {
            selection = this.config.options[optionId].selections[selectionId];
            if (selection.priceType == '0') {
                price = selection.priceValue;
            } else {
                price = (this.config.basePrice*selection.priceValue)/100;
            }
        }
        //price += this.config.options[optionId].selections[selectionId].plusDisposition;
        //price -= this.config.options[optionId].selections[selectionId].minusDisposition;
        //return price*qty;
        var disposition = this.config.options[optionId].selections[selectionId].plusDisposition +
            this.config.options[optionId].selections[selectionId].minusDisposition;

        if (this.config.specialPrice) {
            newPrice = (price*this.config.specialPrice)/100;
            newPrice = (Math.round(newPrice*100)/100).toString();
            price = Math.min(newPrice, price);
        }

        var result = [price*qty];
        return result;
    },

    populateQty: function(optionId, selectionId){
        if (selectionId == '' || selectionId == 'none') {
            this.showQtyInput(optionId, '0', false);
            return;
        }
        if (this.config.options[optionId].selections[selectionId].customQty == 1) {
            this.showQtyInput(optionId, this.config.options[optionId].selections[selectionId].qty, true);
        } else {
            this.showQtyInput(optionId, this.config.options[optionId].selections[selectionId].qty, false);
        }
    },

    showQtyInput: function(optionId, value, canEdit) {
        elem = $('#bundle-option-' + optionId + '-qty-input')[0];
        elem.value = value;
        elem.disabled = !canEdit;
        if (canEdit) {
            $(elem).removeClass('qty-disabled');
        } else {
            $(elem).addClass('qty-disabled');
        }
    },

    changeOptionQty: function (element, event) {
        var checkQty = true;
        if (typeof(event) != 'undefined') {
            if (event.keyCode == 8 || event.keyCode == 46) {
                checkQty = false;
            }
        }
        if (checkQty && (Number(element.value) == 0 || isNaN(Number(element.value)))) {
            element.value = 1;
        }
        parts = element.id.split('-');
        optionId = parts[2];
        if (!this.config['options'][optionId].isMulti) {
            selectionId = this.config.selected[optionId][0];
            this.config.options[optionId].selections[selectionId].qty = element.value*1;
            this.reloadPrice();
        }
    },

    validationCallback: function (elmId, result){
        if (elmId == undefined || $('#'+elmId) == undefined) {
            return;
        }
        var container = $('#'+elmId).parent('ul.options-list');
        if (typeof container != 'undefined') {
            if (result == 'failed') {
                container.removeClass('validation-passed');
                container.addClass('validation-failed');
            } else {
                container.removeClass('validation-failed');
                container.addClass('validation-passed');
            }
        }
    }
}
