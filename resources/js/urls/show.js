import {apiClient} from "../apiClient";
require('./createCheck');
import __ from "lodash";

export const getEntityId = () => {
    return  __.last(window.location.pathname.split('/')) ;
};

$(function () {
    $(document).ready(function () {
        let apiClientParam = {
            "endpoint": ['/api/urls', getEntityId()].join('/'),
            "method": 'GET',
        };
        apiClient(apiClientParam).done(function (response) {
            createBaseUrlInfoTable(response.url);
            createCheckInfoTable(response.checkList.data);
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
                <td>${__.truncate(check.h1, {"length": 30}) ?? ''}</td>
                <td>${__.truncate(check.keywords, {"length": 30}) ?? ''}</td>
                <td>${__.truncate(check.description, {"length": 30}) ?? ''}</td>
                <td>${check.created_at}</td>
            </tr>`);
    });
}