<div class="card row-hover pos-relative py-3 px-3 mb-3 border-warning border-top-0 border-right-0 border-bottom-0 rounded-2">
    <div class="row align-items-center">
        <div class="col-10 mb-3 mb-sm-0">
            <div id = "post-text" class="text-black">"{{$report->text}}"</div>
        </div>
    </div>
    <div class="row align-items-end mt-4">
        <div class="col-10 op-7">
            Date: {{substr($report->date, 0, 19)}}    
        </div>
    </div>
</div>