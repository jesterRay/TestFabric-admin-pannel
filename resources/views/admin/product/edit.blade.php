@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Product')

@section('content')

@php
    $statusOptions = collect([
        (object) ['id' => 'Available', 'name' => 'Available'],
        (object) ['id' => 'Unavailable', 'name' => 'Unavailable'],
    ]);
@endphp

<div class="row">
  <div class="col-12 mb-6">
    <div class="card mb-6">
        <div class="card-body pt-4">
            <div class="card-title text-primary h4 mb-6">Edit Product</div>
            <form method="POST" action="{{ route('product.update', $product->product__ID) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row g-6">
                    <!-- Product Number -->
                    <div class="col-md-6">
                        <label for="product__Number" class="form-label">Item Number *</label>
                        <input 
                            class="form-control" 
                            type="text" 
                            id="product__Number" 
                            name="product__Number" 
                            value="{{ old('product__Number', $product->product__Number) }}" 
                            required
                        />
                    </div>

                    <!-- Product Name -->
                    <div class="col-md-6">
                        <label for="product__Name" class="form-label">Item Name *</label>
                        <input 
                            class="form-control" 
                            type="text" 
                            id="product__Name" 
                            name="product__Name" 
                            value="{{ old('product__Name', $product->product__Name) }}" 
                            required
                        />
                    </div>
            
                    <!-- Product Description -->
                    <div class="col-12">
                        <label for="product__Description" class="form-label">Item Description *</label>
                        <textarea 
                            class="form-control" 
                            id="product__Description" 
                            name="product__Description" 
                            rows="4" 
                            required>{{ old('product__Description', $product->product__Description) }}</textarea>
                    </div>

                    <!-- Show on Home Radio Button -->
                    <div class="row pt-6">
                        <div class="col-md-6">
                            <label for="show_on_home" class="form-label">Show on home?</label>
                            <div class="form-check">
                                <input 
                                    class="form-check-input" 
                                    type="radio" 
                                    name="show_on_home" 
                                    id="show_on_home_yes" 
                                    value="1" 
                                    {{ old('show_on_home', $product->show_on_home) == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_on_home_yes">
                                    Yes
                                </label>
                            </div>
                            <div class="form-check">
                                <input 
                                    class="form-check-input" 
                                    type="radio" 
                                    name="show_on_home" 
                                    id="show_on_home_no" 
                                    value="0" 
                                    {{ old('show_on_home', $product->show_on_home) == '0' ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_on_home_no">
                                    No
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Image Description -->
                    <div class="row pt-6">
                        <div class="col-md-6">
                            <label for="image_description" class="form-label">Image Description *</label>
                            <input 
                                class="form-control" 
                                type="text" 
                                id="image_description" 
                                name="image_description" 
                                value="{{ old('image_description', $product->image_description) }}" 
                                required
                            />
                        </div>
                    </div>

                    <!-- Master Category Dropdown -->
                    <div class="col-md-6">
                        <x-select-two-drop-down
                            name="product__Category_Name"
                            title="Master Category Name *"
                            placeholder="Select..."
                            :options="$categories"
                            :selected="old('product__Category_Name', $product->product__Category_Name)"
                            :multiple="false"
                        />
                    </div>

                    <!-- Subcategory Dropdown -->
                    <div class="col-md-6">
                        <x-select-two-drop-down
                            name="product__Subcategory_Name"
                            title="Subcategory Name *"
                            placeholder="Select..."
                            :options="$subcategories"
                            :selected="old('product__Subcategory_Name', $product->product__Subcategory_Name)"
                            :multiple="false"
                        />
                    </div>

                    <!-- Section Number -->
                    <div class="col-md-6">
                        <label for="product__Section_No" class="form-label">Section Number *</label>
                        <input 
                            class="form-control" 
                            type="text" 
                            id="product__Section_No" 
                            name="product__Section_No" 
                            value="{{ old('product__Section_No', $product->product__Section_No) }}" 
                            required
                        />
                    </div>

                    <!-- Is Equipment Radio Button -->
                    <div class="row pt-6">
                        <div class="col-md-6">
                            <label for="isEquipment" class="form-label">Is Equipment?</label>
                            <div class="form-check">
                                <input 
                                    class="form-check-input" 
                                    type="radio" 
                                    name="isEquipment" 
                                    id="isEquipmentYes" 
                                    value="1" 
                                    {{ old('isEquipment', $product->isEquipment) == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="isEquipmentYes">
                                    Yes
                                </label>
                            </div>
                            <div class="form-check">
                                <input 
                                    class="form-check-input" 
                                    type="radio" 
                                    name="isEquipment" 
                                    id="isEquipmentNo" 
                                    value="0" 
                                    {{ old('isEquipment', $product->isEquipment) == '0' ? 'checked' : '' }}>
                                <label class="form-check-label" for="isEquipmentNo">
                                    No
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Product Weight Gram -->
                    <div class="col-md-6">
                        <label for="product__Weight_gm_m2" class="form-label">Weight grams/meter2: </label>
                        <input 
                            class="form-control" 
                            type="number" 
                            id="product__Weight_gm_m2" 
                            name="product__Weight_gm_m2" 
                            value="{{ old('product__Weight_gm_m2',$product->product__Weight_gm_m2) }}" 
                            required
                        />
                    </div>

                    <!-- Product Weight Yard -->
                    <div class="col-md-6">
                        <label for="product__Weight_Oz_yd2" class="form-label">Weight ounces/yard2: </label>
                        <input 
                            class="form-control" 
                            type="number" 
                            id="product__Weight_Oz_yd2" 
                            name="product__Weight_Oz_yd2" 
                            value="{{ old('product__Weight_Oz_yd2',$product->product__Weight_Oz_yd2) }}" 
                            required
                        />
                    </div>

                    <!-- Product Weight Cm -->
                    <div class="col-md-6">
                        <label for="product__Width_Cm" class="form-label">Width Cm: *</label>
                        <input 
                            class="form-control" 
                            type="number" 
                            id="product__Width_Cm" 
                            name="product__Width_Cm" 
                            value="{{ old('product__Width_Cm',$product->product__Width_Cm) }}" 
                            required
                        />
                    </div>

                    <!-- Product Weight Inches -->
                    <div class="col-md-6">
                        <label for="product__Width_Inches" class="form-label">Width Inches: *</label>
                        <input 
                            class="form-control" 
                            type="number" 
                            id="product__Width_Inches" 
                            name="product__Width_Inches" 
                            value="{{ old('product__Width_Inches',$product->product__Width_Inches) }}" 
                            required
                        />
                    </div>

                    <!-- Available in Dropdown -->
                    <div class="col-md-6">
                        <x-select-two-drop-down
                            name="product__Available"
                            title="Avaiable In *"
                            placeholder="Select..."
                            :options="$availableIn"
                            :selected="old('product__Available',$product->product__Available)"
                            :multiple="false"

                        />
                    </div>

                    <!-- Minimum Order Quantity Dropdown -->
                    <div class="col-md-6">
                        <x-select-two-drop-down
                            name="product__MOQ"
                            title="Minimum Order Quantity *"
                            placeholder="Select..."
                            :options="$availableIn"
                            :selected="old('product__MOQ',$product->product__MOQ)"
                            :multiple="false"
                        />
                    </div>

                    <div class="row pt-6">
                        <!-- Show Product Radio Button -->
                        <div class="col-md-6">
                            <label for="show_product" class="form-label">Show Product?</label>
                            <div class="form-check">
                                <input 
                                    class="form-check-input" 
                                    type="radio" 
                                    name="show_product" 
                                    id="show_product_yes" 
                                    value="1" 
                                    {{ old('show_product',$product->show_product) == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_product_yes">
                                    Yes
                                </label>
                            </div>
                            <div class="form-check">
                                <input 
                                    class="form-check-input" 
                                    type="radio" 
                                    name="show_product" 
                                    id="show_product_no" 
                                    value="0" 
                                    {{ old('show_product',$product->show_product) == '0' ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_product_no">
                                    No
                                </label>
                            </div>
                        </div>
                        
                        <!-- Show on App Radio Button -->
                        <div class="col-md-6">
                            <label for="show_product" class="form-label">Show on App?</label>
                            <div class="form-check">
                                <input 
                                    class="form-check-input" 
                                    type="radio" 
                                    name="show_on_app" 
                                    id="show_on_app_yes" 
                                    value="1" 
                                    {{ old('show_on_app',$product->show_on_app) == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_on_app_yes">
                                    Yes
                                </label>
                            </div>
                            <div class="form-check">
                                <input 
                                    class="form-check-input" 
                                    type="radio" 
                                    name="show_on_app" 
                                    id="show_on_app_no" 
                                    value="0" 
                                    {{ old('show_on_app',$product->show_on_app) == '0' ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_on_app_no">
                                    No
                                </label>
                            </div>
                        </div>
                    </div>


                    <!-- Product Images -->
                    <div class="col-md-6">
                        <label for="img" class="form-label">Product Image *</label>
                        <div class="my-3">
                            @if ($product->img)
                                <img 
                                    id="imgPreview"
                                    src="{{ asset($product->img) }}"
                                    alt="Selected Image"
                                    style="max-width: 100%; height: auto;"
                                />
                            @else
                                <img 
                                    id="imgPreview" 
                                    src="#" 
                                    alt="Selected Image" 
                                    style="max-width: 100%; height: auto;"
                                />
                            @endif
                        </div>
                        <input 
                            class="form-control" 
                            type="file" 
                            id="img" 
                            name="img"
                            onchange="previewImage(event, 'imgPreview')"
                        />
                    </div>
                    <!-- Product Images A-->
                    <div class="col-md-6">
                        <label for="img_a" class="form-label">Product Image *</label>
                        <div class="my-3">
                            @if ($product->img_a)
                                <img 
                                    id="imgPreview_a"
                                    src="{{ asset($product->img_a) }}"
                                    alt="Selected Image"
                                    style="max-width: 100%; height: auto;"
                                />
                            @else
                                <img 
                                    id="imgPreview_a" 
                                    src="#" 
                                    alt="Selected Image" 
                                    style="max-width: 100%; height: auto;"
                                />
                            @endif
                        </div>
                        <input 
                            class="form-control" 
                            type="file" 
                            id="img_a" 
                            name="img_a"
                            accept=".jpg"
                            onchange="previewImage(event, 'imgPreview_a')"
                        />
                    </div>
                    <!-- Product Images B-->
                    <div class="col-md-6">
                        <label for="img_b" class="form-label">Product Image *</label>
                        <div class="my-3">
                            @if ($product->img_b)
                                <img 
                                    id="imgPreview_b"
                                    src="{{ asset($product->img_b) }}"
                                    alt="Selected Image"
                                    style="max-width: 100%; height: auto;"
                                />
                            @else
                                <img 
                                    id="imgPreview_b" 
                                    src="#" 
                                    alt="Selected Image" 
                                    style="max-width: 100%; height: auto;"
                                />
                            @endif
                        </div>
                        <input 
                            class="form-control" 
                            type="file" 
                            id="img_b" 
                            name="img_b"
                            accept=".jpg"
                            onchange="previewImage(event, 'imgPreview_b')"
                        />
                    </div>
                    <!-- Product Images C-->
                    <div class="col-md-6">
                        <label for="img_c" class="form-label">Product Image *</label>
                        <div class="my-3">
                            @if ($product->img_c)
                                <img 
                                    id="imgPreview_c"
                                    src="{{ asset($product->img_c) }}"
                                    alt="Selected Image"
                                    style="max-width: 100%; height: auto;"
                                />
                            @else
                                <img 
                                    id="imgPreview_c" 
                                    src="#" 
                                    alt="Selected Image" 
                                    style="max-width: 100%; height: auto;"
                                />
                            @endif
                        </div>
                        <input 
                            class="form-control" 
                            type="file" 
                            id="img_c" 
                            name="img_c"
                            accept=".jpg"
                            onchange="previewImage(event, 'imgPreview_c')"
                        />
                    </div>
                    <!-- Add other image fields similar to this -->
                </div>

                <div class="mt-6">
                <button type="submit" class="btn btn-primary me-3">Update Product</button>
                </div>
            </form>
        </div>
      </div>
  </div>
</div>

@endsection


@push('script')
    <script>
        // Image preview function
        function previewImage(event,id) {
            const fileInput = event.target;
            const file = fileInput.files[0];
            const preview = document.getElementById(id);

            if (file) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    preview.src = e.target.result; // Set the image source to the file content
                    preview.style.display = 'block'; // Make the image visible
                };

                reader.readAsDataURL(file); // Read the file content
            } else {
                preview.src = "#"; // Reset the image source
                preview.style.display = 'none'; // Hide the image
            }
        }
    </script>
@endpush
