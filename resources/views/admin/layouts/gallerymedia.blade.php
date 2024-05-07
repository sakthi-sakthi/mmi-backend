<div class="overlay-back" style="display: none"></div>
<div class="choose-panel" style="display: none">
    <div class="row m-0">
        <div class="col-sm-12 p-0">
            <div class="card-header">
                <a href="javascript:void(0)" class="btn btn-danger btn-xs float-right" style="opacity: 0"><i class="far fa-times-circle"></i></a>
                <a id="close" href="javascript:void(0)" class="btn btn-danger btn-xs float-right"><i class="far fa-times-circle"></i></a>
            </div>
            <div class="infinite-scroll" style="overflow:auto; height:80vh">
                <div class="scroll-content">
                    <form action="{{ route('admin.gallery.storeMedia') }}" method="post" enctype="multipart/form-data" id="imageUploadForm">
                        @csrf
                        @foreach ($medias as $media)
                            @php
                                $id = $media->id;
                                $mediaItem = $media->getMedia()->first();
                            @endphp
                            @if ($id != 1)
                                <div class="thumbnail">
                                    <input type="checkbox" hidden name="selected_images[]" value="{{ $id }}" id="image{{ $id }}" class="image-checkbox">
                                    <label for="image{{ $id }}">
                                        <img src="{{ $mediaItem->getUrl('thumb') }}" data-url="{{ $mediaItem->getUrl() }}" alt="{{ $mediaItem->alt }}" data-title="{{ $mediaItem->name }}" class="selectable-image">
                                        <i class="fas fa-check tick-mark"></i> 
                                    </label>
                                </div>
                            @endif
                        @endforeach
                        {{ $medias->links() }}
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

