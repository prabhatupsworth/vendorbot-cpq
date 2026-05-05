   @if ($company)
       {{-- ✅ SHOW COMPANY DATA --}}
       <div class="row g-4">

           {{-- Logo --}}
           <div class="col-md-4">
               <div class="text-center p-4 border rounded bg-light-subtle h-100">

                   @if ($company->logo)
                       <img src="{{ asset('storage/' . $company->logo) }}" class="img-fluid rounded mb-3"
                           style="max-height:120px;">
                   @else
                       <div class="text-muted py-4">
                           <i class="ti ti-photo fs-1"></i>
                           <div>No Logo</div>
                       </div>
                   @endif

                   <h6 class="fw-semibold">
                       {{ $company->company_name }}
                   </h6>

                   <small class="text-muted">
                       {{ $company->email ?? '-' }}
                   </small>

               </div>
           </div>

           {{-- Details --}}
           <div class="col-md-8">
               <div class="row g-3">

                   <div class="col-md-6">
                       <div class="detail-box">
                           <small>Contact Person</small>
                           <div class="fw-semibold">
                               {{ $company->contact_name ?? '-' }}
                           </div>
                       </div>
                   </div>

                   <div class="col-md-6">
                       <div class="detail-box">
                           <small>Phone</small>
                           <div class="fw-semibold">
                               {{ $company->phone ?? '-' }}
                           </div>
                       </div>
                   </div>

                   <div class="col-md-12">
                       <div class="detail-box">
                           <small>Address</small>
                           <div class="fw-semibold">
                               {{ $company->address_line1 ?? '' }}
                               {{ $company->address_line2 ?? '' }},
                               {{ $company->city ?? '' }},
                               {{ $company->state ?? '' }},
                               {{ $company->country ?? '' }}
                               {{ $company->postal_code ?? '' }}
                           </div>
                       </div>
                   </div>

               </div>
           </div>

       </div>
   @else
       {{-- ❌ NO COMPANY STATE --}}
       <div class="text-center py-5">

           <i class="ti ti-building fs-1 text-muted mb-3"></i>

           <h6 class="fw-semibold">No Company Details Added</h6>

           <p class="text-muted mb-3">
               Add company information to manage billing, invoices, and integrations.
           </p>

           <button class="btn btn-success" data-bs-toggle="offcanvas" data-bs-target="#companyCanvas">
               <i class="ti ti-plus"></i> Add Company
           </button>

       </div>
   @endif
