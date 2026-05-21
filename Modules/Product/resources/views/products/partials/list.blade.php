 <tr class="product-list" data-id={{ $product->id }}>

     <td>

         <div class="d-flex flex-column">

             <span class="fw-semibold">

                 {{ $product->name }}

             </span>

             <small class="text-muted">

                 {{ \Illuminate\Support\Str::limit($product->description, 50) }}

             </small>

         </div>

     </td>

     <td>

         {{ $product->project?->name ?? '-' }}

     </td>

     <td>

         ₹{{ number_format($product->price, 2) }}

     </td>

     <td>

         @if ($product->discount_type)

             @if ($product->discount_type == 'fixed')
                 ₹{{ number_format($product->discount_value, 2) }}
             @else
                 {{ $product->discount_value }}%
             @endif
         @else
             -

         @endif

     </td>

     <td>

         <span class="fw-semibold text-success">

             ₹{{ number_format($product->final_price, 2) }}

         </span>

     </td>

     <td>

         @if ($product->active)
             <span class="badge badge-soft-success">

                 Active

             </span>
         @else
             <span class="badge badge-soft-danger">

                 Inactive

             </span>
         @endif

     </td>

     <td class="text-end">

         <div class="dropdown table-action">

             <a href="javascript:void(0);" class="action-icon" data-bs-toggle="dropdown">

                 <i class="fa fa-ellipsis-v"></i>

             </a>

             <div class="dropdown-menu dropdown-menu-end">

                 <!-- Edit -->
                 <a href="javascript:void(0);" class="dropdown-item edit-form" data-bs-toggle="offcanvas"
                     data-bs-target="#productCanvas" data-url="{{ route('products.update', $product->id) }}"
                     data-id="{{ $product->id }}" data-method="PUT" data-data='@json($product)'
                     data-form="#productForm">

                     <i class="ti ti-edit text-blue"></i>

                     Edit

                 </a>

                 <!-- Delete -->
                 <form method="POST" action="{{ route('products.destroy', $product->id) }}">

                     @csrf
                     @method('DELETE')

                     <button type="submit" class="dropdown-item delete-btn">

                         <i class="ti ti-trash text-danger"></i>

                         Delete

                     </button>

                 </form>

             </div>

         </div>

     </td>

 </tr>
