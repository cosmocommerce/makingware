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
 * @category    Varien
 * @package     js
 * @copyright   Copyright (c) 2009 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
if(typeof Product=='undefined') {
    var Product = {};
}
Function.prototype.bind = function() {
    if (arguments.length < 2 && undefined == arguments[0]) return this;
    var __method = this, args = $.makeArray(arguments), object = args.shift();
    return function() {
      return __method.apply(object, args.concat($.makeArray(arguments)));
    }
}
/**************************** CONFIGURABLE PRODUCT **************************/
Product.Config = function(config, imageUrl, loadingUrl) {
    var self = this;
    this.config     = config;
    this.imageUrl = imageUrl;
    this.loadingUrl = loadingUrl;
    //this.taxConfig  = this.config.taxConfig;
    this.settings   = $('.super-attribute-select');
    this.state      = [];
    this.priceTemplate = this.config.template;
    this.prices     = config.prices;

    this.settings.each(function(index, element){
    	$(element).change($.proxy(self.configure, self));
        //Event.observe(element, 'change', this.configure.bind(this))
    });

    // fill state
    this.settings.each($.proxy(function(index, element){
        var attributeId = element.id.replace(/[a-z]*/, '');
        if(attributeId && this.config.attributes[attributeId]) {
            element.config = this.config.attributes[attributeId];
            element.attributeId = attributeId;
            this.state[attributeId] = false;
        }
    }, self));

    // Init settings dropdown
    var childSettings = [];
    for(var i=this.settings.length-1;i>=0;i--){
        var prevSetting = this.settings[i-1] ? this.settings[i-1] : false;
        var nextSetting = this.settings[i+1] ? this.settings[i+1] : false;
        if(i==0){
            this.fillSelect(this.settings[i])
        }
        else {
            this.settings[i].disabled=true;
        }
        $(this.settings[i])[0].childSettings = $.extend({}, childSettings);
        $(this.settings[i])[0].prevSetting   = prevSetting;
        $(this.settings[i])[0].nextSetting   = nextSetting;
        childSettings.push(this.settings[i]);
    }

    // try retireve options from url
    var separatorIndex = window.location.href.indexOf('#');
    if (separatorIndex!=-1) {
        var paramsStr = window.location.href.substr(separatorIndex+1);
        this.values = paramsStr.toQueryParams();
        this.settings.each(function(element){
            var attributeId = element.attributeId;
            element.value = this.values[attributeId];
            this.configureElement(element);
        }.bind(this));
    }
}
Product.Config.prototype = {
    configure: function(event){
        var element = event.target;
        this.configureElement(element);
    },

    configureElement : function(element) {
        this.reloadOptionLabels(element);
        if(element.value){
            this.state[element.config.id] = element.value;
            if(element.nextSetting){
                element.nextSetting.disabled = false;
                this.fillSelect(element.nextSetting);
                this.resetChildren(element.nextSetting);
            }
        }
        else {
            this.resetChildren(element);
        }
        this.reloadPrice();
        if (this.imageUrl) {
        	this.reloadImage();
        }
//      Calculator.updatePrice();
    },

    reloadOptionLabels: function(element){
        var selectedPrice;
        if(element.options[element.selectedIndex].config){
            selectedPrice = parseFloat(element.options[element.selectedIndex].config.price)
        }
        else{
            selectedPrice = 0;
        }
        for(var i=0;i<element.options.length;i++){
            if(element.options[i].config){
                element.options[i].text = this.getOptionLabel(element.options[i].config, element.options[i].config.price-selectedPrice);
            }
        }
    },

    resetChildren : function(element){
        if(element.childSettings) {
            for(var i=0;i<element.childSettings.length;i++){
                element.childSettings[i].selectedIndex = 0;
                element.childSettings[i].disabled = true;
                if(element.config){
                    this.state[element.config.id] = false;
                }
            }
        }
    },

    fillSelect: function(element){
        var attributeId = element.id.replace(/[a-z]*/, '');
        var options = this.getAttributeOptions(attributeId);
        this.clearSelect(element);
        element.options[0] = new Option(this.config.chooseText, '');

        var prevConfig = false;
        if(element.prevSetting){
            prevConfig = element.prevSetting.options[element.prevSetting.selectedIndex];
        }

        if(options) {
            var index = 1;
            for(var i=0;i<options.length;i++){
                var allowedProducts = [];
                if(prevConfig && prevConfig.config.allowedProducts) {
                    for(var j=0;j<options[i].products.length;j++){
                    	$.each(prevConfig.config.allowedProducts, function(key, value){
                    		if(value == options[i].products[j]){
                    			allowedProducts.push(value);
                    		}
                    	});
                    }
                } else {
                    allowedProducts = $.extend({}, options[i].products);
                }

                if($.makeArray(allowedProducts).length>0){
                    options[i].allowedProducts = allowedProducts;
                    element.options[index] = new Option(this.getOptionLabel(options[i], options[i].price), options[i].id);
                    element.options[index].config = options[i];
                    index++;
                }
            }
        }
    },

    getOptionLabel: function(option, price){
        var price = parseFloat(price);
        //if (this.taxConfig.includeTax) {
            //var tax = price / (100 + this.taxConfig.defaultTax) * this.taxConfig.defaultTax;
            //var excl = price - tax;
            //var incl = excl*(1+(this.taxConfig.currentTax/100));
       // } else {
            //var tax = price * (this.taxConfig.currentTax / 100);
            var excl = price;
            //var incl = excl + tax;
        //}

        //if (this.taxConfig.showIncludeTax || this.taxConfig.showBothPrices) {
            //price = incl;
        //} else {
            price = excl;
        //}

        var str = option.label;
        if(price){
            //if (this.taxConfig.showBothPrices) {
            //    str+= ' ' + this.formatPrice(excl, true) + ' (' + this.formatPrice(price, true) + ' ' + this.taxConfig.inclTaxTitle + ')';
            //} else {
                str+= ' ' + this.formatPrice(price, true);
            //}
        }
        return str;
    },

    formatPrice: function(price, showSign){
        var str = '';
        price = parseFloat(price);
        if(showSign){
            if(price<0){
                str+= '-';
                price = -price;
            }
            else{
                str+= '+';
            }
        }

        var roundedPrice = (Math.round(price*100)/100).toString();

        if (this.prices && this.prices[roundedPrice]) {
            str+= this.prices[roundedPrice];
        }
        else {
        	str+= this.priceTemplate.replace(/#\{price\}/, price.toFixed(2));
            //str+= this.priceTemplate.evaluate({price:price.toFixed(2)});
        }
        return str;
    },

    clearSelect: function(element){
        for(var i=element.options.length-1;i>=0;i--){
            element.remove(i);
        }
    },

    getAttributeOptions: function(attributeId){
        if(this.config.attributes[attributeId]){
            return this.config.attributes[attributeId].options;
        }
    },

    reloadPrice: function(){
        var price = 0;
        for(var i=this.settings.length-1;i>=0;i--){
            var selected = this.settings[i].options[this.settings[i].selectedIndex];
            if(selected.config){
                price += parseFloat(selected.config.price);
            }
        }

        optionsPrice.changePrice('config', price);
        optionsPrice.reload();

        return price;

        if($('#product-price-'+this.config.productId).length > 0){
            $('#product-price-'+this.config.productId).html(price);
        }
        this.reloadOldPrice();
    },

    reloadOldPrice: function(){
        if ($('#old-price-'+this.config.productId).length > 0) {
            var price = parseFloat(this.config.oldPrice);
            for(var i=this.settings.length-1;i>=0;i--){
                var selected = this.settings[i].options[this.settings[i].selectedIndex];
                if(selected.config){
                    price+= parseFloat(selected.config.price);
                }
            }
            if (price < 0)
                price = 0;
            price = this.formatPrice(price);

            if($('#old-price-'+this.config.productId).length > 0){
                $('#old-price-'+this.config.productId).html(price);
            }
        }
    },
    
    reloadImage: function(){
    	var tagEl = $('.product-img-box');
    	if (!tagEl.size()){return ;}
    	
    	if　(tagEl.data('0') == undefined){
    		tagEl.data('0', tagEl.html());
   	 	}
    	var productId = '0';
        var products = [];
        for(var i=this.settings.length-1;i>=0;i--){
             var selected = this.settings[i].options[this.settings[i].selectedIndex];
             if(selected.config && selected.config.products){
             	if (products.length > 0) {
             		for(var x in products){
             			for(var y in selected.config.products){
             				if(products[x] == selected.config.products[y]){
             					productId = products[x];
             					break;
             				}
             			}
             		}
             	}else {
             		products = selected.config.products;
             		productId = products[0];
             	}
             }
         }
         if (tagEl.data(productId)){
        	 tagEl.html(tagEl.data(productId));
         }else{
        	 if (this.loadingUrl && $("#image").size()){
        		 $('#image').attr('src', this.loadingUrl);
        	 }
        	 $.get(this.imageUrl, {id: productId}, function(html){
        		 if (!html) {html = tagEl.data('0');}
        		 tagEl.data(productId, html).html(html);
        	 });
         }
    }
}

/**************************** SUPER PRODUCTS ********************************/

Product.Super = {};
Product.Super.Configurable = function(container, observeCss, updateUrl, updatePriceUrl, priceContainerId) {
    this.container = $(container);
    this.observeCss = observeCss;
    this.updateUrl = updateUrl;
    this.updatePriceUrl = updatePriceUrl;
    this.priceContainerId = priceContainerId;
    this.registerObservers();
}
Product.Super.Configurable.prototype = {
    registerObservers: function() {
        var elements = this.container.getElementsByClassName(this.observeCss);
        elements.each(function(element){
            Event.observe(element, 'change', this.update.bindAsEventListener(this));
        }.bind(this));
        return this;
    },
    update: function(event) {
        var elements = this.container.getElementsByClassName(this.observeCss);
        var parameters = Form.serializeElements(elements, true);

        new Ajax.Updater(this.container, this.updateUrl + '?ajax=1', {
                parameters:parameters,
                onComplete:this.registerObservers.bind(this)
        });
        var priceContainer = $('#'+this.priceContainerId);
        if(priceContainer.length > 0) {
            new Ajax.Updater(priceContainer, this.updatePriceUrl + '?ajax=1', {
                parameters:parameters
            });
        }
    }
}

/**************************** PRICE RELOADER ********************************/
Product.OptionsPrice = function(config) {
    this.productId          = config.productId;
    this.priceFormat        = config.priceFormat;
    this.includeTax         = config.includeTax;
    this.defaultTax         = config.defaultTax;
    this.currentTax         = config.currentTax;
    this.productPrice       = config.productPrice;
    this.showIncludeTax     = config.showIncludeTax;
    this.showBothPrices     = config.showBothPrices;
    this.productPrice       = config.productPrice;
    this.productOldPrice    = config.productOldPrice;
    this.skipCalculate      = config.skipCalculate;
    this.duplicateIdSuffix  = config.idSuffix;

    this.oldPlusDisposition = config.oldPlusDisposition;
    this.plusDisposition    = config.plusDisposition;

    this.oldMinusDisposition = config.oldMinusDisposition;
    this.minusDisposition    = config.minusDisposition;

    this.optionPrices = {};
    this.containers = {};

    this.displayZeroPrice   = true;

    this.initPrices();
}
Product.OptionsPrice.prototype = {
    setDuplicateIdSuffix: function(idSuffix) {
        this.duplicateIdSuffix = idSuffix;
    },

    initPrices: function() {
        this.containers[0] = 'product-price-' + this.productId;
        this.containers[1] = 'bundle-price-' + this.productId;
        this.containers[2] = 'price-including-tax-' + this.productId;
        this.containers[3] = 'price-excluding-tax-' + this.productId;
        this.containers[4] = 'old-price-' + this.productId;
    },

    changePrice: function(key, price) {
        this.optionPrices[key] = parseFloat(price);
    },

    getOptionPrices: function() {
        var result = 0;
        var nonTaxable = 0;
        $.each(this.optionPrices, function(key, value) {
        	var pair = {key: key, value: value};
            if (pair.key == 'nontaxable') {
                nonTaxable = pair.value;
            } else {
                result += pair.value;
            }
        });
        var r = new Array(result, nonTaxable);
        return r;
    },

    reload: function() {
        var price;
        var formattedPrice;
        var optionPrices = this.getOptionPrices();
        var nonTaxable = optionPrices[1];
        optionPrices = optionPrices[0];
        $.each(this.containers, function(key, value) {
        	var pair = {key: key, value: value};
            var _productPrice;
            var _plusDisposition;
            var _minusDisposition;
            if ($('#'+pair.value).length > 0) {
                if (pair.value == 'old-price-'+this.productId && this.productOldPrice != this.productPrice) {
                    _productPrice = this.productOldPrice;
                    _plusDisposition = this.oldPlusDisposition;
                    _minusDisposition = this.oldMinusDisposition;
                } else {
                    _productPrice = this.productPrice;
                    _plusDisposition = this.plusDisposition;
                    _minusDisposition = this.minusDisposition;
                }

                var price = optionPrices+parseFloat(_productPrice)
                if (this.includeTax == 'true') {
                    // tax = tax included into product price by admin
                    var tax = price / (100 + this.defaultTax) * this.defaultTax;
                    var excl = price - tax;
                    var incl = excl*(1+(this.currentTax/100));
                } else {
                    var tax = price * (this.currentTax / 100);
                    var excl = price;
                    var incl = excl + tax;
                }

                excl += parseFloat(_plusDisposition);
                incl += parseFloat(_plusDisposition);
                excl -= parseFloat(_minusDisposition);
                incl -= parseFloat(_minusDisposition);

                //adding nontaxlable part of options
                excl += parseFloat(nonTaxable);
                incl += parseFloat(nonTaxable);

                if (pair.value == 'price-including-tax-'+this.productId) {
                    price = incl;
                } else if (pair.value == 'old-price-'+this.productId) {
                    if (this.showIncludeTax || this.showBothPrices) {
                        price = incl;
                    } else {
                        price = excl;
                    }
                } else {
                    if (this.showIncludeTax) {
                        price = incl;
                    } else {
                        if (!this.skipCalculate || _productPrice == 0) {
                            price = excl;
                        } else {
                            price = optionPrices+parseFloat(_productPrice);
                        }
                    }
                }

                if (price < 0) price = 0;

                if (price > 0 || this.displayZeroPrice) {
                    formattedPrice = this.formatPrice(price);
                } else {
                    formattedPrice = '';
                }

                if ($('#'+pair.value+'.price:first').length > 0) {
                	$('#'+pair.value+'.price:first').html(formattedPrice);
                    if ($('#'+pair.value+this.duplicateIdSuffix) && $('#'+pair.value+this.duplicateIdSuffix+'.price:first').length > 0) {
                    	$('#'+pair.value+this.duplicateIdSuffix+'.price:first').html(formattedPrice);
                    }
                } else {
                	$('#'+pair.value).html(formattedPrice);
                    if ($('#'+pair.value+this.duplicateIdSuffix)) {
                    	$('#'+pair.value+this.duplicateIdSuffix).html(formattedPrice);
                    }
                }
            };
        }.bind(this));
    },
    formatPrice: function(price) {
        return formatCurrency(price, this.priceFormat);
    }
}