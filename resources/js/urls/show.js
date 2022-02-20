require('../ajaxSetup');
require('./createCheck');
import __ from "lodash";

export const getEntity = () => {
    return $('#entity').html()
};

$(function () {
    $(document).ready(function () {
        let endpoint = 'http://0.0.0.0:8000/api/urls'
        $.ajax({
            type: "GET",
            url:  [endpoint, JSON.parse(getEntity()).id].join('/'),
            success: function (response) {
                createBaseUrlInfoTable(response.url);
                createCheckInfoTable(response.checkList.data);
            },
            error: function (response) {
                console.log(response)
            }
        })
    })
});

const createBaseUrlInfoTable = (url) => {
    $('#h1_url_name').append(url.name);
    $('#table_url_name').append(`<td>${url.name}</td>`);
    $('#id').append(`<td>${url.id}</td>`);
    $('#created_at').append(`<td>${url.created_at}</td>`);
    $('#updated_at').append(`<td>${url.updated_at}</td>`);
};

export const createCheckInfoTable = (checkData) => {
    checkData.forEach(check => {
        $('#check_results').append(
            `<tr>
                <td>${check.id}</td>
                <td>${check.status_code}</td>
                <td>${__.truncate(check.h1, {"length": 50}) ?? ''}</td>
                <td>${__.truncate(check.keywords, {"length": 50}) ?? ''}</td>
                <td>${__.truncate(check.description, {"length": 50}) ?? ''}</td>
                <td>${check.created_at}</td>
            </tr>`);
    });
}