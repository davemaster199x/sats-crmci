function ajax(url, data) {
  return $.ajax({
    type: 'POST',
    url: url,
    data: data,
    dataType: 'json',
    crossDomain: true,
    headers: { 'X-Requested-With': 'XMLHttpRequest' },
    error: function (res) {
      console.log('error');
      console.log(res);
    },
    success: function (response) {},
  });
}

function initFlatpickr(
  inputElement,
  enableTime = false,
  minDate = false,
  onChangeCallback = null
) {
  const config = {
    enableTime: !enableTime,
    dateFormat: !enableTime ? 'Y-m-d h:i K' : 'Y-m-d',
    time_24hr: false, // Use 12-hour time format with AM/PM
    allowInput: true,
    altInput: true,
    altFormat: !enableTime ? 'F j, Y h:i K' : 'F j, Y',
  };

  if (minDate) {
    config['minDate'] = 'today';
  }
  if (onChangeCallback) {
    config.onChange = onChangeCallback;
  }

  return flatpickr(inputElement, config);
}
