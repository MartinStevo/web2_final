function getLanguage(lang) {

}

function getlocationdata(next) {
    $.getJSON('languages/', next);
}

function insert_data_into_db(data, page_url, next) {

    data.local_time = new Date().getHours();
    data.page = page_url;
    $.ajax({
        url: 'filldb.php',
        type: 'POST',
        data: JSON.stringify(data),
        contentType: 'application/json',
        dataType: 'text',
        success: function(result) {
            console.log(result);
            if (next !== undefined) next();
        },
        error: function(req, err) {
            console.log(err);
        },
    });
}
