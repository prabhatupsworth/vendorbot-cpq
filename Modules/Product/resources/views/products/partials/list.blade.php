  @forelse($products as $product)
      <tr class="product-list" data-id={{ $product->id }}>

          <td>

              <div class="d-flex flex-column">

                  <span class="fw-semibold text-capitalize">

                      {{ $product->name }}

                  </span>

                  {{-- <small class="text-muted">

                 {{ \Illuminate\Support\Str::limit($product->description, 50) }}

             </small> --}}

              </div>

          </td>

          <td>

              {{ $product->pipedrive_product_id ?? '-' }}

          </td>

          <td>

              {{ currency($product->cost) }}

          </td>

          <td>

              {{ currency($product->price) }}

          </td>

          <td>

              <span class="fw-semibold">

                  {{ $product->product_code ?? '-' }}

              </span>

          </td>

          <td>

              @if ($product->active)
                  <span class="badge bg-soft-success">

                      Active

                  </span>
              @else
                  <span class="badge bg-soft-danger">

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
                      <a class="dropdown-item delete-btn" href="#"
                          data-url="{{ route('products.destroy', $product->id) }}"><i
                              class="ti ti-trash text-danger"></i>
                          Delete</a>

                  </div>

              </div>

          </td>

      </tr>
  @empty

      <tr>

          <td colspan="10" class="text-center py-5">

              <div class="text-muted">

                  No products found.

              </div>

          </td>

      </tr>
  @endforelse
