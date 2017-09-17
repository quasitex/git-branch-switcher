$(document).ready(function() {
	$('body').append('<div id="_branchswitcher"><select id="_branchswitcher-branch" name="_branchswitcher-branch"></div>');

	$('#_branchswitcher-branch').on('focus', function () {
        	this.previous = $(this).val();
	}).change(function() {
		if (!confirm('Please, confirm switching to branch "'+$('#_branchswitcher-branch').val()+'"...')) {
			$(this).val(this.previous);

			return;
		}
		switchToBranch($('#_branchswitcher-branch').val());
	});

	getBranches();
});

function getBranches() {
	var config = getConfig();
	
	$.ajax({
		url: getServerUrl(),
		dataType: 'json',
		data: {
			showRemote: config.showRemote
		},
		beforeSend: function() {
			disableControls();
			$('#_branchswitcher-branch').empty();
		},
		success: function(data) {
			data.branches.forEach(function(branch) {
				var option = $('<option value="'+branch.id+'">'+branch.name+'</option>');
				if (branch.current) {
					option.attr('selected', true);
				}
				if (branch.remote) {
					option.addClass('remote');
				}
				$('#_branchswitcher-branch').append(option);
			});			
		},
		failure: function() {
			alert('Branches list fetching failed. Click information button to see more details.');
		},
		complete: function() {
			enableControls();
		}
	});
}

function switchToBranch(branch) {
	var config = getConfig();
	
	$.ajax({
		url: getServerUrl(),
		method: 'POST',
		dataType: 'json',
		data: {
			branch: branch
		},
		beforeSend: function() {
			disableControls();
		},
		failure: function(data) {
			alert('Switch to branch "'+branch+'" failed. Click information button to see more details.');
		},
		complete: function() {
			getBranches();
		}
	});
}

function getConfig() {
	var json = $.cookie('_branchswitcher');
	if (!json) {
		return setConfig({
			showRemote: false
		});
	}
	return JSON.parse(json);
}

function setConfig(config) {
	$.cookie('_branchswitcher', JSON.stringify(config), {
		expires: 365
	});
	return config;
}

function disableControls() {
	// disable buttons
	$('#_branchswitcher-branch').prop('disabled', true);
}

function enableControls() {
	// enable buttons
	$('#_branchswitcher-branch').prop('disabled', false);
}

function getServerUrl() {
	if ($('#branchswitcher-script').length) {
		var url = $('#branchswitcher-script')[0].src;
		return url.substring(0, url.lastIndexOf('/'))+'/switcher.php';
	} 
	return '/branch-switcher/switcher.php';
}
