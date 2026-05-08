 <tr class="satege-mapping"  data-id="{{ $automation->id }}">

     <!-- Stage -->
     <td>

         <div class="d-flex align-items-center gap-2">

             <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center"
                 style="width:40px;height:40px;">

                 <i class="ti ti-git-branch"></i>

             </div>

             <div>

                 <h6 class="mb-0 fw-semibold">
                     {{ $automation->stage?->name ?? 'N/A' }}
                 </h6>

                 <small class="text-muted">
                     Pipeline Stage
                 </small>

             </div>

         </div>

     </td>

     <!-- Trigger -->
     <td>

         <span class="badge bg-light text-dark border px-3 py-2">

             ENTER STAGE

         </span>

     </td>

     <!-- Action -->
     <td>

         <span class="badge bg-primary px-3 py-2">

             {{ strtoupper($automation->action_type) }}

         </span>

     </td>

     <!-- Status -->
     <td>

         <span class="badge bg-success-subtle text-success border border-success-subtle">

             <i class="ti ti-check me-1"></i>

             Active

         </span>

     </td>

     <!-- Actions -->
     <td class="text-end">

         <div class="d-flex justify-content-end gap-2">

             <button class="btn btn-sm btn-light border edit-form" data-bs-toggle="offcanvas"
                 data-bs-target="#automationCanvas"
                 data-url="{{ route('projects.stages.update', [$projectId, $automation->id]) }}" data-method="PUT"
                 data-data='@json($automation)' data-form="#stageMappingForm">

                 <i class="ti ti-edit"></i>

             </button>

             <button class="btn btn-sm btn-light border text-danger delete-btn"
                 data-url="{{ route('projects.stages.delete', [$projectId, $automation->id]) }}">

                 <i class="ti ti-trash"></i>

             </button>

         </div>

     </td>

 </tr>
