window.makingware = window.makingware || {};

makingware.utility = {
	isNumber: function(value){
		return parseInt(value).toString().length == value.length;
	}
};

makingware.form = function(scope, config){
	this.scope = scope;
	this.scope.data('this', this);

	this.error = {
		required: 'Required Fields',
		minLength: 'At least %s characters',
		maxLength: 'Up to %s characters',
		number1: 'Must be a number',
		number2: 'Must be %s digits',
		regular: 'Invalid Input',
		url: 'Invalid %s address',
		email: 'Invalid %s address',
		ip: 'Invalid %s address',
		username: 'Invalid user name'
	};

	if(config && $.isPlainObject(config)) {
		if(config.error && $.isPlainObject(config.error)){
			for(var key in config.error){this.error[key] = config.error[key];}
			delete config.error;
		}
		for(var key in config){this[key] = config[key];}
	}
};
makingware.form._instance = {};
makingware.form.getInstance = function(scope, config, initValidata){
	if (scope instanceof jQuery) {$scope = scope;} else {$scope = $(scope);}
	if(!(this._instance[$scope.selector])){
		this._instance[$scope.selector] = new this($scope, config);

		if(initValidata == undefined) {initValidata = true;}
		if(initValidata) {this._instance[$scope.selector].init();}
	}
	return this._instance[$scope.selector];
};

makingware.form.prototype = {
	getScope: function(scope) {
		if (typeof(scope) == 'string') {
			$scope = $(scope);
		}else if(typeof(scope) == 'object') {
			if (scope instanceof jQuery) {
				$scope = scope;
			}else {
				$scope = $(scope);
			}
		}else {
			$scope = this.scope;
		}
		return $scope;
	},

	validata: function(scope) {
		var $self = this, $this = $self.getScope(scope);

		if (! $this.attr('validata')) {return $self;}

		var validata = {}, rule = $this.attr('validata').split(',');
		for (var i=0; i<rule.length; i++) {var tmp = rule[i].split(':'); validata[tmp[0]] = tmp[1];}

		$this.data('error', []).data('callback', {});
		/*---validata Begin---*/
        if(validata['required'] && validata['required']=='true'){
        	$this.data('required', true);
        	$this.data('callback').required = function(){
            	if($.trim($this.val())==""){
                	$this.data('error').push($self.error.required);
                }
            };
        }
        if(validata['minLength'] && makingware.utility.isNumber(validata['minLength'])){
        	$this.data('callback').minLength = function(){
            	if($this.val().length<parseInt(validata['minLength'])){
            		$this.data('error').push($self.error.minLength.replace('%s', validata['minLength']));
                }
            };
        }
        if(validata['maxLength'] && validata['maxLength']){
        	$this.data('callback').maxLength = function(){
            	if($this.val().length>parseInt(validata['maxLength'])){
            		$this.data('error').push($self.error.maxLength.replace('%s', validata['maxLength']));
                }
            };
        }
        if(validata['number']){
        	$this.data('callback').number1 = function(){
                if(!(/\d+/.test($this.val()))){$this.data('error').push($self.error.number1);}
            };
            if(makingware.utility.isNumber(validata['number'])){
            	$this.data('callback').number2 = function(){
                	if($this.val().length!=parseInt(validata['number'])){
                		$this.data('error').push($self.error.number2.replace('%s', validata['number']));
                	}
                };
            }
        }
        if(validata['regular']){
        	$this.data('callback').regular = function(){
            	if(!(validata['regular'].test($this.val()))){
                	$this.data('error').push($self.error.regular);
                }
            };
        }
        if(validata['method']){
        	$this.data('callback').method = function(){
        		var msg = 'Input errors';
        		var result = (eval(validata['method']))();
        		if($.isPlainObject(result)){
        			if(result.fail || result.error){
        				if(result.msg){msg = result.msg;}
        				if(result.message){msg = result.message;}
        				$this.data('error').push(msg);
        			}
        		}else if(!result){
        			$this.data('error').push(msg);
        		}
            };
        }
        if(validata['url'] && validata['url']=='true'){
        	$this.data('callback').url = function(){
            	if(!(/[a-zA-z]+:\/\/[^s]*/.test($this.val()))){
            		$this.data('error').push($self.error.url.replace('%s', 'url'));
            	}
            };
        }
        if(validata['email'] && validata['email']=='true'){
        	$this.data('callback').email = function(){
            	if(!(/^(?:[a-z\d]+[_\-\+\.]?)*[a-z\d]+@(?:([a-z\d]+\-?)*[a-z\d]+\.)+([a-z]{2,})+$/i.test($this.val()))){
            		$this.data('error').push($self.error.email.replace('%s', 'email'));
            	}
            };
        }
        if(validata['ip'] && validata['ip']=='true'){
        	$this.data('callback').ip = function(){
            	if(!(/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/.test($this.val()))){
            		$this.data('error').push($self.error.ip.replace('%s', 'IP'));
            	}
            };
        }
        if(validata['username'] && validata['username']=='true'){
        	$this.data('callback').username = function(){
        		if(!(/^[a-zA-Z\u4e00-\u9fa5][a-zA-Z\d\-_\u4e00-\u9fa5]{2,19}$/i.test($this.val()))){
            		$this.data('error').push($self.error.username);
            	}
            };
        }
        /*---validata End---*/

        // find prompt element
        var prompt = $this.siblings('.prompt-msg');
        if(!(prompt.length)){prompt = $this.parent().find('.prompt-msg');}
        if(!(prompt.length)){prompt = $(scope).find('.prompt-'+$this.attr('name'));}
        if(prompt.size()) {
        	$this.data('prompt', prompt);
        }
        return $self;
	},

	init: function(scope) {
		var $self = this, $scope = $self.getScope(scope), $iter = $scope.find("*[validata]");

		if($iter.size() <= 0) {return $self;}

		 // total
		$iter.live('focus', function(){
			var $this = $(this);
			if (undefined == $this.data('callback')) {$self.validata($this);}

			if($this.data('prompt')) {
				$this.addClass('focus');
				$this.data('prompt').removeClass('prompt-error');
				if($this.data('prompt').attr('message')) {
					$this.data('prompt').text($this.data('prompt').attr('message'));
				}
			}
		});

		$iter.live('blur', function(){
			var $this = $(this);
			if (undefined == $this.data('callback')) {$self.validata($this);}

			$this.removeClass('focus').removeClass('error');
			$this.data('error', []).data('validataed', true);
			if(false == $.isEmptyObject($this.data('callback'))) {
	        	$.each($this.data('callback'), function(){
	        		if ($this.data('required') == true || $this.val() != "") {
	        			this();
	        		}
	        	});
	        	if($this.data('error').length > 0){
	        		$this.addClass('error');
	        		if ($this.data('prompt')) {
	        			$this.data('prompt').addClass('prompt-error').html($this.data('error').join(', '));
	        		}
	        		$this.data('validataed', false);
	            }else{
	            	if ($this.data('prompt')) {$this.data('prompt').empty();}
	            }
			}
		});
		return $self;
	},

	unvalidata: function(scope){
		var $iter = this.getScope(scope).find("*[validata]");
		if($iter.size()) {
			$iter.removeAttr('validata').die('focus').die('blur');
		}
		return this;
	},

	isValidata: function(scope){
		$iter = this.getScope(scope).find("*[validata]");
		$iter.trigger('blur');

		var error = [];
		$iter.each(function(){
			if (false == $(this).data('validataed')) {error.push($(this));}
		});
		return error.length == 0;
	},

	submit: function(fun){
		return this.scope.submit(fun);
	}
};
