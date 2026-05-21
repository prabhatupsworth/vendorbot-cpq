      <div class="col-md-4 user-card" data-id="{{ $user->id }}">

          <div class="user-card p-3 rounded-3 border bg-white h-100 position-relative">

              <!-- 🔹 Top Section -->
              <div class="d-flex align-items-center gap-3">

                  <!-- Avatar -->
                  <div class="avatar-lg">
                      {{ strtoupper(substr($user->name, 0, 1)) }}
                  </div>

                  <!-- Info -->
                  <div class="flex-grow-1">
                      <h6 class="mb-0 fw-semibold">{{ $user->name }}</h6>
                      <small class="text-muted">
                          {{ $user->email ?? 'No Email' }}
                      </small>
                  </div>

                  <!-- Role Badge -->
                  <span
                      class="badge role-badge
                                {{ $role === 'admin' ? 'bg-success' : 'bg-secondary' }}">
                      {{ ucfirst($role ?? 'user') }}
                  </span>
              </div>

              <!-- 🔹 Divider -->
              <hr class="my-3">

              <!-- 🔹 Actions -->
              <div class="d-flex justify-content-between align-items-center">

                  <small class="text-muted">
                      Added to project
                  </small>

                  <div class="d-flex gap-2">
                      <button data-url="{{ route('projects.users.remove', [$projectId, $user->id]) }}"
                          class="btn btn-sm btn-light border text-danger delete-btn">
                          <i class="ti ti-trash"></i>
                      </button>
                  </div>

              </div>

          </div>

      </div>
