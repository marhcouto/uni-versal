<form method="GET">
    <div class="row mb-3">
                
        <div class="col-8" id="filter-order-bar">

    <!-- Questions filter/order-->

            <label class="mr-3 ml-3"> Filter By: </label>
            <select class="custom-select custom-select-s h-100 w-auto" id="filter-method" name="filter-method">
                <option value="none" {{ old('filter-method') == 'none' ? 'selected' : '' }}>No filter</option>
                <option value="solved" {{ old('filter-method') == 'solved' ? 'selected' : '' }}>Solved</option>
                <option value="unsolved" {{ old('filter-method') == 'unsolved' ? 'selected' : '' }}>Unsolved</option>
                <option value="no-replies" {{ old('filter-method') == 'no-replies' ? 'selected' : '' }}>No replies yet</option>
            </select>

            <label class="mr-3 ml-3"> Order By: </label>
            <select class="custom-select custom-select-s h-100 w-auto" id="order-method" name="order-method">
            <option value="relevance" {{ old('order-method') == 'relevance' ? 'selected' : '' }}>Relevance</option>
            <option value="latest" {{ old('order-method') == 'latest' ? 'selected' : '' }}>Latest</option> 
            <option value="popularity" {{ old('order-method') == 'popularity' ? 'selected' : '' }}>Popularity</option>
            <option value="answers" {{ old('order-method') == 'answers' ? 'selected' : '' }}>Nº Answers</option>
            </select>

        
            <input id="radio-button-search-order-way1" value="ASC" class="form-check-input mt-3" type="radio" {{ old('order-way') == 'ASC' ? 'checked' : '' }} name="order-way">
            <label for="radio-button-search-order-way1" class="form-check-label"><i class="icon-1x bi bi-arrow-up"></i></label> 
            <input id="radio-button-search-order-way2" value="DESC" class="form-check-input mt-3" type="radio" {{ old('order-way') == 'DESC' ? 'checked' : '' }} name="order-way">
            <label for="radio-button-search-order-way" class="form-check-label"><i class="icon-1x bi bi-arrow-down"></i></label>

            <label class="mr-3 ml-3"> Topic: </label>
            <select class="custom-select custom-select-s h-100 w-auto mr-2" id="select-topics-navbar" name="select-topics-navbar">
                <option value="all-topics">All topics</option>
                @foreach ($topicarea as $area => $topics)
                    <optgroup label="{{ $area }}">
                        @foreach ($topics as $topic)
                            <option value="{{ $topic }}" {{ old('select-topics-navbar') == $topic  ? 'selected' : '' }}>{{ $topic }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>

        </div>
        <div class="col-4">
            <button type="submit" id="filter-button-questions" class="btn btn-primary float-end search-window-buttons" formaction="{{route('filter-questions', ['baseInput' => $baseInput])}}">
                Apply
            </button>
        </div>

    </div>
</form>