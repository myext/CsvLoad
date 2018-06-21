@if(isset($model) && !empty($model))
    <style>
        .zvg_error {
            color: red;
        }
        .zvg_success {
            color: green;
        }
        .zvg_load {
            background: url({{url("zvg/img/progress_bar.gif")}}) no-repeat;
            background-position: center center;
            background-size: 98%;
        }
        #zvgCsvFile input[type="file"]{
            display: none;
        }
        #csvServerRequest {
            min-height: 30px;
            padding: 10px;
            text-align: center;
        }
        #zvgForm {
            width: 250px;
        }
        #zvgLabel {
            position: relative;
            overflow: hidden;
            width: 250px;
            height: 40px;
            background: #4169E1;
            border-radius: 10px;
            color: #fff;
            text-align: center;
        }
        #zvgLabel:hover,
        #zvgSubmit:hover {
            background: #1E90FF;
        }
        #zvgLabel label {
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        #zvgLabel span {
            display: block;
            padding: 10px;
        }
        #zvgSubmit {
            position: relative;
            overflow: hidden;
            background: #4169E1;
            border-radius: 10px;
            color: #fff;
            text-align: center;
            width: 100px;
            height: 30px;
            border: 0;
            outline: 0 !important;
        }


    </style>
    <div id="zvgForm">
        <form id="zvgCsvFile" enctype="multipart/form-data" method="post" action="{{ route('zvg.csvload') }}">
            <input type="hidden" name="model" value="{{ $model }}">
            <div id="zvgLabel">
                <label>
                    <span id="zvgFile">
                        {{trans('zvg::messages.select_file')}}
                    </span>
                    <input type="file" name="zvg_csv_file" onchange="zvgGetName(this); return false;">
                </label>
            </div>
            <div id="csvServerRequest"></div>
            <input id="zvgSubmit" type="submit" value="{{trans('zvg::messages.send_file')}}" onclick="zvgSendCsv(); return false">
        </form>
    </div>


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

            csvServerRequest.className = 'zvg_load';

            xhr.onreadystatechange = function() {
                if (xhr.readyState != 4) return;

                if (xhr.status == 200) {

                    csvServerRequest.className = "";

                    var data = JSON.parse(xhr.responseText);

                    csvServerRequest.className = 'zvg_' + data.type;

                    csvServerRequest.textContent = data.message;
                }
            }
        }

        function zvgGetName(file) {
            var fileName = file.value;
            zvgFile.textContent = fileName.split('\\').pop().split('/').pop();
        }
    </script>
@endif