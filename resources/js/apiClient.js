import {alertMessage} from "./message";

export default $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    },
    contentType:'application/json',
    dataType: "json",
});

export const apiClient = (param) => {
    return $.ajax({
        url: param.endpoint,
        method: param.method,
        data: param.data,
        success: function (response) {
                if (param.method === 'post') {
                    alertMessage(response, param.action);
                }
            },
        error: function (response) {
            alertMessage(JSON.parse(response.responseText), param.action);
        },
    });
}