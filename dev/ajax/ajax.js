function AutoSuggestControl(oTextbox, oProvider) {
	this.provider = oProvider;
	this.textbox = oTextbox;
	this.init();
}

/*
 * Determines the browser and creates a text selection box that is supports.
 */
AutoSuggestControl.prototype.selectRange = function (iStart, iLength) {
	if (this.textbox.createTextRange) {
		var oRange = this.textbox.createTextRange();
		oRange.moveStart("character", iStart);
		oRange.moveEnd("character", iLength - this.textbox.value.length);
		oRange.select();
	} else if (this.textbox.setSelectionRange) {
		this.textbox.setSelectionRange(iStart, iLength);
	}

	this.textbox.focus();
};


AutoSuggestControl.prototype.typeAhead = function (sSuggestion) {
	if (this.textbox.createTextRange || this.textbox.setSelectionRange) {
		var iLen = this.textbox.value.length;
		this.textbox.value = sSuggestion;
		this.selectRange(iLen, sSuggestion.length);
	}
};

AutoSuggestControl.prototype.autosuggest = function (aSuggestions) {
	if (aSuggestions.length > 0) {
		//DEBUG
		if(aSuggestions[0].indexOf('...')!=-1)
			aSuggestion[0]="fail"
		else
		this.typeAhead(aSuggestions[0]);
	}
};

AutoSuggestControl.prototype.handleKeyUp = function (oEvent) {
	var iKeyCode = oEvent.keyCode;

	if (iKeyCode < 32 || (iKeyCode >= 33 && iKeyCode <= 46) || (iKeyCode >= 112 && iKeyCode <= 123)) {
		//ignore
	} else {
		this.provider.requestSuggestions(this);
	}
};

AutoSuggestControl.prototype.init = function () {
	var oThis = this;
	this.textbox.onkeyup = function (oEvent) {
		if (!oEvent) {
			oEvent = window.event;
		}
		oThis.handleKeyUp(oEvent);
	};
};


function SuggestionProvider() {
	//any initializations needed go here
}


SuggestionProvider.prototype.requestSuggestions = function (oAutoSuggestControl) {

	var aSuggestions = new Array();

	//determine suggestions for the control
	oAutoSuggestControl.autosuggest(aSuggestions);
};

function StateSuggestions() {
	    this.states = [
"Alabama", "Alaska", "Arizona", "Arkansas",
"California", "Colorado", "Connecticut",
"Delaware", "Florida", "Georgia", "Hawaii",
"Idaho", "Illinois", "Indiana", "Iowa",
"Kansas", "Kentucky", "Louisiana",
"Maine", "Maryland", "Massachusetts", "Michigan", "Minnesota",
"Mississippi", "Missouri", "Montana",
"Nebraska", "Nevada", "New Hampshire", "New Mexico", "New York",
"North Carolina", "North Dakota", "Ohio", "Oklahoma", "Oregon",
"Pennsylvania", "Rhode Island", "South Carolina", "South Dakota",
"Tennessee", "Texas", "Utah", "Vermont", "Virginia",
"Washington", "West Virginia", "Wisconsin", "Wyoming"
];
}


StateSuggestions.prototype.requestSuggestions = function (oAutoSuggestControl) {
	var aSuggestions = [];
	var sTextboxValue = oAutoSuggestControl.textbox.value;

	if (sTextboxValue.length > 0){
		for (var i=0; i < this.states.length; i++) {
			if (this.states[i].indexOf(sTextboxValue) == 0) {
				aSuggestions.push(this.states[i]);
			}
		}
	oAutoSuggestControl.autosuggest(aSuggestions);
	}
};
