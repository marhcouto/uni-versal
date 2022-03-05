<form method="GET">
    <div class="row mb-3">
        <div class="col-9" id="filter-order-bar">

                <!-- Profile filter/order-->

            <label class="mr-3 ml-3"> Role: </label>
            <select class="custom-select custom-select-s h-100 w-auto" id="filter-method" name="filter-method-role">
                <option value="all" {{ old('filter-method-role') == 'all' ? 'selected' : '' }}>All</option>
                <option value="professor" {{ old('filter-method-role') == 'professor' ? 'selected' : '' }}>Professor</option>
                <option value="student" {{ old('filter-method-role') == 'student' ? 'selected' : '' }}>Student</option>
            </select>

            <label class="mr-3 ml-3"> Rank: </label>
            <select class="custom-select custom-select-s h-100 w-auto" id="filter-method" name="filter-method-rank">
                <option value="all" {{ old('filter-method-rank') == 'all' ? 'selected' : '' }}>All</option>
                <option value="user" {{ old('filter-method-rank') == 'user' ? 'selected' : '' }}>User</option>
                <option value="moderator" {{ old('filter-method-rank') == 'moderator' ? 'selected' : '' }}>Moderator</option>
                <option value="admin" {{ old('filter-method-rank') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>

        </div>
        <div class="col">
            <button type="submit" id="filter-button-users" class="btn btn-primary float-end search-window-buttons" formaction="{{route('filter-users', ['baseInput' => $baseInput])}}">
                Apply
            </button>
        </div>
    </div>

</form>