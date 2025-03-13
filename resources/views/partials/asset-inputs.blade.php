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
            <!-- Image upload section -->
            <input
                name="img[{{$item['assetHeaderData']->asset_no}}]"
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
                name="img_hidden[{{$item['assetHeaderData']->asset_no}}][]"
                id="imgHiddenInput_{{ $index }}"
                style="display: none;"
                multiple>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Fetch index from the input attribute
                const index = "{{ $index }}";
                const imgPicker = document.getElementById(`imgPicker_${index}`);
                const addToBasket = document.getElementById(`addToBasket_${index}`);
                const fileBasket = document.getElementById(`fileBasket_${index}`);
                const imgHiddenInput = document.getElementById(`imgHiddenInput_${index}`);
                let basketFiles = [];

                // Add selected files to the basket on 'Add to Basket' click
                addToBasket.addEventListener('click', () => {
                    const files = Array.from(imgPicker.files);

                    files.forEach(file => {
                        if (file.type.startsWith('image/')) {
                            basketFiles.push(file);

                            // Display file in basket list
                            const li = document.createElement('li');
                            li.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');
                            li.innerHTML = `${file.name}
                                <button type="button" class="btn btn-danger btn-sm remove-file" data-file-name="${file.name}">Remove</button>`;
                            fileBasket.appendChild(li);
                        }
                    });

                    imgPicker.value = ''; // Clear the file input for new selections
                    updateHiddenInput();
                });

                // Remove file from the basket and update hidden input
                fileBasket.addEventListener('click', (event) => {
                    if (event.target.classList.contains('remove-file')) {
                        const fileName = event.target.getAttribute('data-file-name');
                        basketFiles = basketFiles.filter(file => file.name !== fileName);
                        event.target.parentElement.remove();
                        updateHiddenInput();
                    }
                });

                // Update the hidden file input with current basket files using DataTransfer
                function updateHiddenInput() {
                    const dataTransfer = new DataTransfer();
                    basketFiles.forEach(file => dataTransfer.items.add(file));
                    imgHiddenInput.files = dataTransfer.files;
                }

                // On form submission, ensure the hidden input has the latest files
                document.querySelector('form').addEventListener('submit', function () {
                    updateHiddenInput();
                });
            });
        </script>
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
