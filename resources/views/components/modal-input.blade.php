<div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    @method("$method")

                    @foreach($inputs as $input)
                    <div class="form-group">
                        <label for="{{ $input->id }}" class="col-form-label">{{ $input->label }}</label>

                        @isset($input->textarea)
                        <textarea 
                            id="{{ $input->id }}"
                            class="form-control" 
                            name="{{ $input->name }}" 
                            rows="{{ $input->textarea->rows ?? 4 }}"
                            placeholder="{{ $input->placeholder ?? '' }}"
                        ></textarea>
                        @else
                        <input 
                            id="{{ $input->id }}" 
                            class="form-control" 
                            name={{ $input->name }} 
                            type="{{ $input->type ?? 'text' }}" 
                            value="{{ $input->value ?? '' }}"
                            placeholder="{{ $input->placeholder ?? '' }}" >
                        @endif

                    </div>
                    @endforeach

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>