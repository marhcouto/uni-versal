<div class="row mt-3">
    <hr class="mt-4  question-answers-divider">
    <form method="POST" action = "{{route('createAnswer', ['id_question' => $id_question])}}" class="text-start"  data-bs-toggle="validator" enctype='multipart/form-data' autocomplete="off">
                    @csrf      

                    <h3 class="mt-5 mb-3 text-primary" id="add-answer-section-title">Answer</h3>
                        
                    <textarea required class="form-control mb-3" rows="15" placeholder="Body" style="resize: none;" name = "text"></textarea>

                    <input class="form-control form-control-sm w-50 mb-5" name = 'images[]' id="formFileSm" max-size="2000" type="file" accept="image/*" multiple> Max File Size: 2 MB </input>
                    <input type="checkbox" value = true class="form-check-input" id="anonymous" name = "anonymous">
                    <label class="form-check-label" for="anonymous">Post anonymously</label>


                    <button type="submit" class="btn btn-primary float-end" id="add-answer-button" value="Add Answer">Add Answer</button>
    </form>
</div>