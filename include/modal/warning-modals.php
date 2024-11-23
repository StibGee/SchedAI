
    <!-- Warning Modal -->
    <div class="modal fade" id="warningModal" tabindex="-1" aria-labelledby="warningModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="modal-warnings">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body my-3">
                    <h2 class="modal-title d-flex justify-content-center mb-3" id="warningModalLabel">
                        <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i> Warning
                    </h2>
                    <p class="text-center">The total teaching hours don't match the hours allocated to the faculty. Please adjust this to avoid any issues when scheduling.</p>
                    <div class="row d-flex justify-content-center mt-5">
                        <div class="col-5">
                            Total Schedule Hours:
                        </div>
                        <div class="col-5">
                            Total Faculty Hours:
                        </div>
                    </div>
                    <div class="row d-flex justify-content-around mt-4">
                        <div class="col-4 ">
                            <button type="button" class="btn-wrng p-2 w-100" id="modifyHoursBtn" data-bs-toggle="modal" data-bs-target="#modifyHoursModal1">Modify Faculty Teaching Hours</button>
                        </div>
                        <div class="col-4 ">
                            <button type="button" class="btn-wrng p-2 w-100" id="addFacultyBtn">Add new Faculty</button>
                        </div>
                    </div>
                    <div class="modal-footer mt-2">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modify Hours nested-Modal -->
    <div class="modal fade" id="modifyHoursModal" tabindex="-1" aria-labelledby="modifyHoursModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="modal-warnings">
                <div class="modal-header">
                    <h5 class="modal-title" id="modifyHoursModalLabel">Modify Faculty Teaching Hours</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="row">
                        <div class="col-6">
                            <div class="col-5">
                                Total Schedule Hours:
                            </div>
                            <div class="col-5">
                                Total Faculty Hours:
                            </div>
                        </div>
                        <div class="col-6">
                            <input type="text" id="searchBar" class="form-control" placeholder="Search by name...">
                        </div>
                    </div>

                    <table class="table p-3 mt-3 table-bordered text-center align-middle" id="vvv">
                        <thead class="table-secondary">
                            <tr>
                                <th>Name</th>
                                <th>Rank</th>
                                <th>Teaching Hours</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>John Doe</td>
                                <td>Regular</td>
                                <td id="hours-1">10</td>
                                <td>
                                    <button type="button" class="btn btn-success btn-sm add-hours-btn" data-id="1">Add Hours</button>
                                </td>
                            </tr>
                            <tr>
                                <td>John Doe</td>
                                <td>Regular</td>
                                <td id="hours-1">10</td>
                                <td>
                                    <button type="button" class="btn btn-success btn-sm add-hours-btn" data-id="2">Add Hours</button>
                                </td>
                            </tr>
                            <tr>
                                <td>John Doe</td>
                                <td>Regular</td>
                                <td id="hours-1">10</td>
                                <td>
                                    <button type="button" class="btn btn-success btn-sm add-hours-btn" data-id="3">Add Hours</button>
                                </td>
                            </tr>

                            <!-- Add more rows if needed -->
                        </tbody>
                    </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success">Save Changes</button>
                    </div>
            </div>
        </div>
    </div>
<!-- Outer Modal (Warning Modal) -->
<div class="modal fade" id="warningModal1" tabindex="-1" aria-labelledby="warningModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="modal-warnings">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body my-3">
                <h2 class="modal-title d-flex justify-content-center mb-3" id="warningModalLabel">
                    <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i> Warning
                </h2>
                <p class="text-center">Some faculty members have not yet completed their profiles. Please ensure all profiles are updated to avoid any scheduling issues.</p>

                <div class="row d-flex justify-content-center mt-4">
                    <div class="col-4">
                        <!-- Trigger for Nested Modal -->
                        <button type="button" class="btn-wrng p-2 w-100" id="addFacultyBtn" data-bs-toggle="modal" data-bs-target="#modifyHoursModal">
                            Check faculty
                        </button>
                    </div>
                </div>
                <div class="modal-footer mt-2">
                    <!-- Footer content (if any) -->
                </div>
            </div>
        </div>
    </div>
</div>

</script>

    <!-- Bootstrap JS -->

    <script>
        document.getElementById('openModifyHoursModal').addEventListener('click', function () {
            // Close the first modal
            const warningModal = document.getElementById('warningModal');
            const modifyHoursModal = new bootstrap.Modal(document.getElementById('modifyHoursModal'));

            bootstrap.Modal.getInstance(warningModal).hide(); // Close Warning Modal
            warningModal.addEventListener('hidden.bs.modal', () => {
                modifyHoursModal.show(); // Show Modify Hours Modal after the first is hidden
            }, { once: true });
        });
    </script>

<script>
    document.querySelectorAll('.add-hours-btn').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.id; // Get the row ID
            const hoursCell = document.getElementById(`hours-${id}`); // Find the corresponding hours cell

            // Replace hours text with an input field
            hoursCell.innerHTML = `
                <input type="number" class="form-control form-control-sm" id="input-hours-${id}"
                value="${hoursCell.textContent.trim()}" min="0" />
            `;

            // Change button text and functionality to save
            this.textContent = "Save";
            this.classList.remove('btn-primary');
            this.classList.add('btn-success');

            // Add save functionality
            this.addEventListener('click', function saveHandler() {
                const inputField = document.getElementById(`input-hours-${id}`);
                hoursCell.textContent = inputField.value; // Save the new value
                this.textContent = "Add Hours"; // Reset button text
                this.classList.remove('btn-success');
                this.classList.add('btn-primary');
                this.removeEventListener('click', saveHandler); // Remove save handler
            });
        });
    });
</script>
