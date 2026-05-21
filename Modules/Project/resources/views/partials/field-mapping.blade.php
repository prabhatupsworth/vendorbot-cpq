 <tr class="field-mappling-list" data-id="{{ $mapping->id }}">
     <!-- System Field -->
     <td>

         <div class="d-flex align-items-center gap-3">

             <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center"
                 style="width:42px;height:42px;">

                 <i class="ti ti-database"></i>

             </div>

             <div>

                 <h6 class="mb-0 fw-semibold">

                     {{ config('system_fields')[$mapping->system_field] ?? $mapping->system_field }}

                 </h6>

                 <small class="text-muted">

                     Internal System Field

                 </small>

             </div>

         </div>

     </td>

     <!-- Pipedrive Field -->
     <td>

         <div class="d-flex align-items-center gap-2">

             <span class="badge bg-primary px-3 py-2 fw-medium">

                 {{ $mapping->pipedriveField?->name ?? $mapping->pipedrive_field_key }}

             </span>

         </div>

     </td>

     <!-- Actions -->
     <td class="text-end pe-4">

         <div class="d-flex justify-content-end gap-2">

             <!-- Edit -->
             <button class="btn btn-sm btn-light border edit-form" data-bs-toggle="offcanvas"
                 data-bs-target="#fieldMappingCanvas" data-id="{{ $mapping->id }}" data-method="POST"
                 data-data='@json($mapping)' data-form="#fieldMappingForm">

                 <i class="ti ti-edit"></i>

             </button>

             <!-- Delete -->
             <button class="btn btn-sm btn-light border text-danger delete-btn"
                 data-url="{{ route('projects.field-mappings.delete', [$projectId, $mapping->id]) }}">

                 <i class="ti ti-trash"></i>

             </button>

         </div>

     </td>

 </tr>
