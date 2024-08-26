document.addEventListener('DOMContentLoaded', function() {
    const steps = document.querySelectorAll('.step');
    const stepContents = document.querySelectorAll('.step-content');
    const yearLevelLabel = document.getElementById('year-level-label');
    const nextButtons = document.querySelectorAll('.next-step');
    const prevButtons = document.querySelectorAll('.prev-step');
    const submitBtn = document.getElementById('submitBtn');
    const wizardForm = document.getElementById('wizardForm');

    let currentStep = 0;

    function updateStep(stepIndex) {
        steps.forEach((step, index) => {
            if (index === stepIndex) {
                step.classList.add('active');
                stepContents[index].classList.add('active');
            } else {
                step.classList.remove('active');
                stepContents[index].classList.remove('active');
            }

            // Mark all previous steps as completed
            if (index < stepIndex) {
                step.classList.add('completed');
            } else {
                step.classList.remove('completed');
            }
        });

        const yearLevels = ['First Year', 'Second Year', 'Third Year', 'Fourth Year'];
        yearLevelLabel.textContent = yearLevels[stepIndex];
    }

    nextButtons.forEach(button => {
        button.addEventListener('click', () => {
            if (currentStep < steps.length - 1) {
                currentStep++;
                updateStep(currentStep);
            }
        });
    });

    prevButtons.forEach(button => {
        button.addEventListener('click', () => {
            if (currentStep > 0) {
                currentStep--;
                updateStep(currentStep);
            }
        });
    });

    submitBtn.addEventListener('click', () => {
        // Perform form validation if needed
        if (validateForm()) {
            wizardForm.submit();
            window.location.href = 'setup-acadplan.php';
        }
    });

    function validateForm() {
        // Add your form validation logic here
        return true; // Return false if validation fails
    }

    // Initialize the first step
    updateStep(currentStep);
});
