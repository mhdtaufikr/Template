@extends('layouts.master')

@section('content')
<main>
    <header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
        <div class="container-fluid px-4">
            <div class="page-header-content pt-4">

            </div>
        </div>
    </header>



    <div class="container-fluid px-4 mt-n10">
        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">List of Your View</h3>
                                </div>

                                <div class="card-body">
                                    <div class="container">
                                        <h1 class="mb-4">Edit Signatures for Audit - {{ $audit->audit_no }}</h1>

                                        <form id="signatureForm" action="{{ route('audit.update', encrypt($audit->id)) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            <div class="row">
                                                <!-- Audit Signature -->
                                                <div class="col-md-6 mb-3 text-center">
                                                    <label class="form-label fw-bold">Audit Signature</label>
                                                    <canvas class="border rounded signatureCanvas" id="auditSignatureCanvas"></canvas>
                                                    <input type="hidden" id="auditSignature" name="audit_signature">
                                                    <button type="button" class="btn btn-danger mt-2 clearSignature" data-canvas="auditSignatureCanvas">Clear</button>
                                                </div>

                                                <!-- Controlling Signature -->
                                                <div class="col-md-6 mb-3 text-center">
                                                    <label class="form-label fw-bold">Controlling Signature</label>
                                                    <canvas class="border rounded signatureCanvas" id="controllingSignatureCanvas"></canvas>
                                                    <input type="hidden" id="controllingSignature" name="controlling_signature">
                                                    <button type="button" class="btn btn-danger mt-2 clearSignature" data-canvas="controllingSignatureCanvas">Clear</button>
                                                </div>
                                            </div>

                                            <hr>
                                            <h3 class="mb-3">Asset Signatures & Images</h3>

                                            @foreach ($data as $index => $item)
                                            <div class="col-md-12">
                                                <div class="row border p-3 mb-4 rounded shadow-sm bg-light">
                                                    <h5 class="fw-bold text-primary">Asset: {{ $item['assetHeaderData']->desc }}</h5>

                                                    <!-- Asset Details -->
                                                    <div class="col-md-6">
                                                        <p><strong>Asset No:</strong> {{ $item['assetHeaderData']->asset_no }}</p>
                                                        <p><strong>Location:</strong> {{ $item['assetHeaderData']->loc }}</p>
                                                        <p><strong>Category:</strong> {{ $item['assetHeaderData']->asset_type }}</p>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <p><strong>Status:</strong>
                                                            @php
                                                            $statusText = $item['assetHeaderData']->status == 1 ? 'Active' : ($item['assetHeaderData']->status == 0 ? 'Deactive' : 'Disposal');
                                                            $statusColor = $item['assetHeaderData']->status == 1 ? 'btn-success' : ($item['assetHeaderData']->status == 0 ? 'btn-warning' : 'btn-danger');
                                                            @endphp
                                                            <button class="btn btn-sm {{ $statusColor }}">{{ $statusText }}</button>
                                                        </p>
                                                    </div>

                                                    <!-- Availability & Condition -->
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">Availability</label>
                                                        <select class="form-control" name="availability[{{ $item['assetHeaderData']->asset_no }}]">
                                                            <option value="">-- Select Option --</option>
                                                            <option value="Yes">Yes</option>
                                                            <option value="No">No</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">Condition</label>
                                                        <select class="form-control" name="condition[{{ $item['assetHeaderData']->asset_no }}]">
                                                            <option value="">-- Select Condition --</option>
                                                            <option value="Good">Good</option>
                                                            <option value="NG">Not Good</option>
                                                        </select>
                                                    </div>

                                                    <!-- Remarks -->
                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label fw-bold">Remarks</label>
                                                        <textarea name="remarks[{{ $item['assetHeaderData']->asset_no }}]" cols="30" rows="4" class="form-control"></textarea>
                                                    </div>

                                                    <div class="col-md-12 mb-3">
                                                        <label class="form-label fw-bold">Upload Images</label>

                                                        <!-- Image Upload Input -->
                                                        <input type="file" class="form-control mt-2 imageInput" accept="image/*" multiple data-index="{{ $index }}" id="imgPicker_{{ $index }}">

                                                        <!-- Add to Basket Button -->
                                                        <button type="button" class="btn btn-primary mt-2" id="addToBasket_{{ $index }}">Add to Basket</button>

                                                        <!-- File List Display -->
                                                        <ul class="list-group mt-3 fileBasket" id="fileBasket_{{ $index }}"></ul>

                                                        <!-- Hidden Input to Hold Images -->
                                                        <input type="file" name="img_hidden[{{ $item['assetHeaderData']->asset_no }}][]" id="imgHiddenInput_{{ $index }}" style="display: none;" multiple>
                                                    </div>

                                                   <!-- Asset Signatures -->
                                                   <div class="col-md-12 mb-3">
                                                    <label class="form-label fw-bold">Asset Signature for {{ $item['assetHeaderData']->asset_no }}</label>
                                                    <canvas class="border rounded signatureCanvas" id="assetSignatureCanvas-{{ $item['auditDetail']->id }}"></canvas>
                                                    <input type="hidden" id="assetSignature{{ $item['auditDetail']->id }}" name="asset_signatures[{{ $item['auditDetail']->id }}]">
                                                    <button type="button" class="btn btn-danger mt-2 clearSignature" data-canvas="assetSignatureCanvas-{{ $item['auditDetail']->id }}">Clear</button>
                                                </div>


                                                </div>
                                            </div>
                                            @endforeach

                                            <div class="text-center mt-4">
                                                <button type="submit" class="btn btn-success px-4">Update Signatures</button>
                                                <a href="{{ route('audits.index') }}" class="btn btn-secondary px-4">Back</a>
                                            </div>
                                        </form>
                                    </div>

                                    <script src="https://cdn.jsdelivr.net/npm/signature_pad"></script>
                                    <script>
                                        document.addEventListener("DOMContentLoaded", function () {
                                            let signaturePads = {};

                                            function initializeSignature(canvasId, inputId, existingSignature) {
                                                const canvas = document.getElementById(canvasId);
                                                const input = document.getElementById(inputId);

                                                if (!canvas) {
                                                    console.warn(`Canvas not found: ${canvasId}`);
                                                    return;
                                                }

                                                const signaturePad = new SignaturePad(canvas, {
                                                    backgroundColor: 'white',
                                                    penColor: 'black',
                                                });

                                                // Load existing signature if available
                                                if (existingSignature) {
                                                    const img = new Image();
                                                    img.src = existingSignature;
                                                    img.onload = function () {
                                                        const ctx = canvas.getContext("2d");
                                                        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                                                    };
                                                }

                                                // Save signature data on endStroke
                                                signaturePad.addEventListener("endStroke", () => {
                                                    input.value = signaturePad.toDataURL();
                                                });

                                                // Store the signaturePad instance
                                                signaturePads[canvasId] = signaturePad;
                                            }

                                            // Initialize Audit and Controlling Signatures
                                            initializeSignature("auditSignatureCanvas", "auditSignature", "{{ $audit->signature_aud }}");
                                            initializeSignature("controllingSignatureCanvas", "controllingSignature", "{{ $audit->signature_ctl }}");

                                            // ✅ Correct Foreach Loop for Asset Signatures
                                            @foreach ($auditDetails as $detail)
                                                initializeSignature("assetSignatureCanvas-{{ $detail->id }}", "assetSignature{{ $detail->id }}", "{{ $detail->signature }}");
                                            @endforeach

                                            // ✅ Handle Clear Signature Button Click
                                            document.querySelectorAll(".clearSignature").forEach(button => {
                                                button.addEventListener("click", function () {
                                                    const canvasId = this.getAttribute("data-canvas");
                                                    if (signaturePads[canvasId]) {
                                                        signaturePads[canvasId].clear();
                                                        document.getElementById(`assetSignature${canvasId.split('-')[1]}`).value = "";
                                                    }
                                                });
                                            });

                                            // ✅ Validate before form submission
                                            document.getElementById("signatureForm").addEventListener("submit", function (event) {
                                                let hasSignatures = false;
                                                Object.keys(signaturePads).forEach(canvasId => {
                                                    if (!signaturePads[canvasId].isEmpty()) {
                                                        hasSignatures = true;
                                                        document.getElementById(`assetSignature${canvasId.split('-')[1]}`).value = signaturePads[canvasId].toDataURL();
                                                    }
                                                });

                                                if (!hasSignatures) {
                                                    event.preventDefault();
                                                    alert("⚠ Please sign all required fields before submitting.");
                                                }
                                            });

                                        });
                                    </script>

                                    <script>
                                        document.addEventListener("DOMContentLoaded", () => {
                                            document.querySelectorAll(".imageInput").forEach(imgPicker => {
                                                const index = imgPicker.getAttribute("data-index");
                                                const addToBasket = document.getElementById(`addToBasket_${index}`);
                                                const fileBasket = document.getElementById(`fileBasket_${index}`);
                                                const imgHiddenInput = document.getElementById(`imgHiddenInput_${index}`);
                                                let basketFiles = [];

                                                // ✅ Add selected images to basket
                                                addToBasket.addEventListener("click", () => {
                                                    const files = Array.from(imgPicker.files);

                                                    files.forEach(file => {
                                                        if (file.type.startsWith("image/")) {
                                                            basketFiles.push(file);

                                                            // ✅ Display file name in basket list
                                                            const li = document.createElement("li");
                                                            li.classList.add("list-group-item", "d-flex", "justify-content-between", "align-items-center");
                                                            li.innerHTML = `${file.name}
                                                                <button type="button" class="btn btn-danger btn-sm remove-file" data-file-name="${file.name}">Remove</button>`;
                                                            fileBasket.appendChild(li);
                                                        }
                                                    });

                                                    imgPicker.value = ""; // ✅ Clear file input after adding
                                                    updateHiddenInput();
                                                });

                                                // ✅ Remove file from basket
                                                fileBasket.addEventListener("click", (event) => {
                                                    if (event.target.classList.contains("remove-file")) {
                                                        const fileName = event.target.getAttribute("data-file-name");
                                                        basketFiles = basketFiles.filter(file => file.name !== fileName);
                                                        event.target.parentElement.remove();
                                                        updateHiddenInput();
                                                    }
                                                });

                                                // ✅ Update the hidden input with basket files
                                                function updateHiddenInput() {
                                                    const dataTransfer = new DataTransfer();
                                                    basketFiles.forEach(file => dataTransfer.items.add(file));
                                                    imgHiddenInput.files = dataTransfer.files;
                                                }

                                                // ✅ Ensure hidden input has correct files before submitting
                                                document.querySelector("form").addEventListener("submit", function () {
                                                    updateHiddenInput();
                                                });
                                            });
                                        });
                                        </script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Modal Structure -->
    <div class="modal fade" id="modal-detail" tabindex="-1" aria-labelledby="modal-detail-label" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal-body-content">
                    <!-- Dynamic content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</main>

<style>
    table td {
        white-space: nowrap;  /* Prevent text wrapping */
        overflow: hidden;     /* Hide overflow text */
        text-overflow: ellipsis; /* Show ellipsis for overflow */
    }
</style>

<style>
    .modal-lg-x {
    max-width: 90%;
}
.modal-lg {
    max-width: 70%;
}
</style>
<script>
    $(document).ready(function() {
        var table = $("#openReportsTable").DataTable({
            "responsive": true,  // Enable responsive mode
            "lengthChange": false,  // Disable length change dropdown
            "autoWidth": false,  // Disable auto width to prevent table from stretching out
            "paging": true,  // Enable pagination
            "searching": true,  // Enable search functionality
            "info": false  // Disable table info
        });
    });
</script>


@endsection