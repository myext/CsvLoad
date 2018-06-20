@if(isset($model) && !empty($model))
    <form enctype="multipart/form-data" method="post" action="{{ route('zvg.csvload') }}">
        <p>
            <input type="hidden" name="model" value="{{ $model }}">
            <input type="file" name="zvg_csv_file"><br>
            <input type="submit" value="Отправить">
        </p>
    </form>
@endif
