@if(isset($model) && !empty($model))
    <style>
        .zvg_error {
            color: red;
        }
        .zvg_success {
            color: green;
        }
        #csvServerRequest {
            min-height: 30px;
            padding: 10px;
        }
    </style>

    <form id="zvgCsvFile" enctype="multipart/form-data" method="post" action="{{ route('zvg.csvload') }}">
        <p>
            <input type="hidden" name="model" value="{{ $model }}">
            <label>
                {{trans('zvg::messages.select_file')}}
                <br>
                <input type="file" name="zvg_csv_file">
            </label>
            <br>
        <div id="csvServerRequest"></div>
        <input type="submit" value="{{trans('zvg::messages.send_file')}}" onclick="zvgSendCsv(); return false">
        </p>
    </form>


    <script type="text/javascript">

        function zvgSendCsv() {

            csvServerRequest.className = "";
            csvServerRequest.textContent = "";

            var form = document.forms.zvgCsvFile;
            var formData = new FormData(form);
            var model = form.elements.model.value;

            formData.append("model", model);

            var xhr = new XMLHttpRequest();
            xhr.open("POST", form.getAttribute('action'));
            xhr.send(formData);

            csvServerRequest.textContent = "load...";

            xhr.onreadystatechange = function() {
                if (xhr.readyState != 4) return;

                if (xhr.status == 200) {

                    var data = JSON.parse(xhr.responseText);

                    csvServerRequest.className = 'zvg_' + data.type;

                    csvServerRequest.textContent = data.message;
                }
            }
        }
    </script>
@endif