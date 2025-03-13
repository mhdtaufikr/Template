<div class="col-md-4">
    <div class="row">
        <div class="col-md-6 mb-3">
            <strong>Availability</strong>
            <select class="form-control" name="availability[{{ $asset->asset_no }}]">
                <option value="">-- Select Option --</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <strong>Condition</strong>
            <select class="form-control" name="condition[{{ $asset->asset_no }}]">
                <option value="">-- Select Condition --</option>
                <option value="Good">Good</option>
                <option value="NG">Not Good</option>
            </select>
        </div>
        <div class="col-md-12 mb-3">
            <strong>Images</strong>
            <input
                name="img[{{ $asset->asset_no }}]"
                type="file"
                accept="image/*"
                class="form-control mt-2 imageInput"
                data-index="{{ $index }}"
                id="imgPicker_{{ $index }}">
            <button type="button" id="addToBasket_{{ $index }}" class="btn btn-primary mt-2">Add to Basket</button>
            <ul class="list-group mt-3 fileBasket" id="fileBasket_{{ $index }}"></ul>

            <!-- Hidden file input to hold files for submission -->
            <input
                type="file"
                name="img_hidden[{{ $asset->asset_no }}][]"
                id="imgHiddenInput_{{ $index }}"
                style="display: none;"
                multiple>
        </div>
        <div class="col-md-12 mb-3">
            <strong>Remarks</strong>
            <textarea name="remarks[{{ $asset->asset_no }}]" cols="30" rows="4" class="form-control"></textarea>
        </div>
        <div class="col-md-12">
            <strong>User Signature</strong>
            <canvas id="userSignatureCanvas-{{ $index }}" class="border rounded"></canvas>
            <input type="hidden" id="userSignature-{{ $index }}" name="user_signature[{{ $asset->asset_no }}]">
            <button type="button" class="btn btn-danger mt-2 clearSignature" data-canvas="userSignatureCanvas-{{ $index }}">Clear</button>
        </div>
    </div>
</div>
