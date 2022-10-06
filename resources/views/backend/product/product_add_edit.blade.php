@extends('backend.admin_master')

@section('admin')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Inventory Features</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Product</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- SELECT2 EXAMPLE -->
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">{{ $title }}</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <form action="{{ route('product.add.edit') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" class="form-control" name="product_name" placeholder="product" @if(!empty(@$edit_product -> product_name)) value="{{ $edit_product -> product_name }}" @else value="{{ old('product_name') }}" @endif>

                    @error('product_name')
                        <span class="text-danger">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>
                  <!-- /.form-group -->
                  <div class="form-group">
                    <label>Product Price</label>
                    <input type="text" class="form-control" name="product_price" placeholder="product Discount" @if(!empty(@$edit_product -> product_price)) value="{{ $edit_product -> product_price }}" @else value="{{ old('product_price') }}" @endif >

                    @error('product_price')
                        <span class="text-danger">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>
                  <div class="form-group">
                    <label>Product Discount (%)</label>
                    <input type="text" class="form-control" name="product_discount" placeholder="product Discount" @if(!empty(@$edit_product -> product_discount)) value="{{ $edit_product -> product_discount }}" @else value="{{ old('product_discount') }}" @endif>
                  </div>
                  <div class="form-group">
                    <label>Product Color</label>
                    <input type="text" class="form-control" name="product_color" placeholder="product color" @if(!empty(@$edit_product -> product_color)) value="{{ $edit_product -> product_color }}" @else value="{{ old('product_color') }}" @endif>
                  </div>
                  
                  <div class="form-group">
                    <label>Meta Title</label>
                    <input type="text" class="form-control" name="meta_title" placeholder="meta_title" @if(!empty(@$edit_product -> meta_title)) value="{{ $edit_product -> meta_title }}" @else value="{{ old('meta_title') }}" @endif>
                  </div>
                  <div class="form-group">
                    <label>Product Code </label>
                    <input type="text" class="form-control" name="product_code" placeholder="product_code" @if(!empty(@$edit_product -> product_code)) value="{{ $edit_product -> product_code }}" @else value="{{ old('product_code') }}" @endif>
                    
                    @error('product_code')
                        <span class="text-danger">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>

                  <div class="form-group">
                    <label>Product Fabric</label>
                    <select id="" class="form-control select2" style="width: 100%;" name="fabric">
                      <option value="" selected > -Select- </option>
                      @foreach($fabricArr as $item)
                      <option value="{{ $item }}" @if(!empty(@$edit_product -> fabric) && $edit_product -> fabric == $item) selected @endif>{{ ucwords($item) }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="form-group">
                    <label>Product Fit</label>
                    <select id="" class="form-control select2" style="width: 100%;" name="fit">
                        <option value="" selected > -Select- </option>
                        @foreach($fitArr as $item)
                        <option value="{{ $item }}" @if(!empty(@$edit_product -> fit) && $edit_product -> fit == $item) selected @endif>{{ ucwords($item) }}</option>
                        @endforeach
                      </select>
                  </div>

                  <div class="form-group">
                    <label>Product Discription </label><br>
                    <textarea name="description" id="" style="width: 100%;" rows="2" placeholder="Description. . ." @if(!empty(@$edit_product -> description)) value="{{ $edit_product -> description }}" @else value="{{ old('description') }}" @endif></textarea>
                  </div>
                  <div class="form-group">
                    <label>Meta Keywords</label><br>
                    <textarea name="meta_keyword" id="" style="width: 100%;" rows="2" placeholder="Description. . ." @if(!empty(@$edit_product -> meta_keyword)) value="{{ $edit_product -> meta_keyword }}" @else value="{{ old('meta_keyword') }}" @endif></textarea>
                  </div>
                </div>
                <!-- /.col -->
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Select Category</label>
                    <select id="" class="form-control select2" style="width: 100%;" name="category_id">
                      <option value="" selected > -Select- </option>
                      @foreach($section as $item)
                      <optgroup label="{{ ucwords($item -> name) }}"></optgroup>

                            @foreach($item['getCategory'] as $cat)
                            <option value="{{ $cat -> id }}" @if(!empty(@old('category_id')) && @old('category_id') == $cat -> id) selected @endif > &nbsp; -- &nbsp; {{ ucwords($cat -> category_name) }}</option>

                                @foreach($cat['subcategories'] as $subcat)
                                    <option value="{{ $subcat -> id }}" @if(!empty(@old('category_id')) && @old('category_id') == $subcat -> id) selected @endif >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -- &nbsp;{{ ucwords($subcat -> category_name) }}</option>
                                @endforeach

                            @endforeach

                      @endforeach
                    </select>
                    @error('category_id')
                        <span class="text-danger">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>
                  <!-- /.form-group -->
                  <div class="form-group">
                    <label for="exampleInputFile">Product Image</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="main_image" name="main_image">
                        <label class="custom-file-label" for="main_image">Choose file</label>
                      </div>
                      <div class="input-group-append">
                        <span class="input-group-text">Upload</span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="product_video">Product Video</label>
                    <div class="input-group">
                      <div class="custom-file">

                        <input type="file" class="custom-file-input" id="product_video" name="product_video">
                        <label class="custom-file-label" for="product_video">Choose Video</label>

                      </div>
                      <div class="input-group-append">
                        <span class="input-group-text">Upload</span>
                      </div>
                      <input type="hidden" name="old_video" @if(!empty(@$edit_product -> product_video)) value="{{ $edit_product -> product_video }}" @endif>
                    </div>
                  </div>
                  <!-- /.form-group -->

                  <div class="form-group">
                    <label>Product Weight</label>
                    <input type="text" class="form-control" name="product_weight" placeholder="product weight" @if(!empty(@$edit_product -> product_weight)) value="{{ $edit_product -> product_weight }}" @else value="{{ old('product_weight') }}" @endif>

                    @error('product_weight')
                        <span class="text-danger">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                  </div>

                  <div class="form-group">
                    <label>Product Pattern</label>
                    <select id="" class="form-control select2" style="width: 100%;" name="pattern">
                        <option value="" selected > -Select- </option>
                        @foreach($patternArr as $item)
                        <option value="{{ $item }}" @if(!empty(@$edit_product -> pattern) && $edit_product -> pattern == $item) selected @endif>{{ ucwords($item) }}</option>
                        @endforeach
                      </select>
                  </div>
                  <div class="form-group">
                    <label>Product Ocassion</label>
                    <select id="" class="form-control select2" style="width: 100%;" name="occassion">
                        <option value="" selected > -Select- </option>
                        @foreach($ocassionArr as $item)
                        <option value="{{ $item }}" @if(!empty(@$edit_product -> occassion) && $edit_product -> occassion == $item) selected @endif>{{ ucwords($item) }}</option>
                        @endforeach
                      </select>
                  </div>

                  <div class="form-group">
                    <label>Product Sleeve</label>
                    <select id="" class="form-control select2" style="width: 100%;" name="sleeve">
                      <option value="" selected > -Select- </option>
                      @foreach($sleeveArr as $item)
                      <option value="{{ $item }}" @if(!empty(@$edit_product -> sleeve) && $edit_product -> sleeve == $item) selected @endif>{{ ucwords($item) }}</option>
                      @endforeach
                    </select>
                  </div>


                  <div class="form-group">
                    <label>Product Wash</label>
                    <textarea name="wash_care" id="" style="width: 100%;" rows="2" placeholder="product wash. . ." @if(!empty(@$edit_product -> wash_care)) value="{{ $edit_product -> wash_care }}" @else value="{{ old('wash_care') }}" @endif></textarea>
                  </div>
                  
                  <div class="form-group">
                    <label>Meta Description</label><br>
                    <textarea name="meta_desc" id="" style="width: 100%;" rows="2" placeholder="Description. . ." @if(!empty(@$edit_product -> meta_desc)) value="{{ $edit_product -> meta_desc }}" @else value="{{ old('meta_desc') }}" @endif></textarea>
                  </div>

                  <div class="form-group">
                    <label>Featured Item</label><br>
                    <input type="checkbox" id="is_featured" name="is_featured" class="form-control-checkbox" value="1">
                    <label for="is_featured" @if(!empty(@$edit_product -> is_featured == 1)) checked  @endif>Featured Product</label>
                  </div>
                  
                </div>
                <!-- /.col -->
                <button type="submit" class="btn btn-primary">Submit</button>

              </div>
              <!-- /.row -->
            </form>
          </div>
        </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

@endsection