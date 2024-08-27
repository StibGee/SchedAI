document.addEventListener('DOMContentLoaded', function() {
    const steps = document.querySelectorAll('.step');
    const stepContents = document.querySelectorAll('.step-content');
    const yearLevelLabel = document.getElementById('year-level-label');
    const nextButtons = document.querySelectorAll('.next-step');
    const prevButtons = document.querySelectorAll('.prev-step');

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
});
   // qualifiication
   document.getElementById('degree').addEventListener('change', function() {
    const degree = this.value;
    const specializationContainer = document.getElementById('specialization-container');
    const specializationSelect = document.getElementById('specialization');

    // Clear previous options
    specializationSelect.innerHTML = '<option selected disabled value="">Choose...</option>';

    if (degree) {
        specializationContainer.style.display = 'block';

        if (degree === 'PhD') {
            addOption(specializationSelect, 'Computer Science', 'CS');
            addOption(specializationSelect, 'Physics', 'Physics');
            addOption(specializationSelect, 'Mathematics', 'Math');
        } else if (degree === 'MD') {
            addOption(specializationSelect, 'Cardiology', 'Cardiology');
            addOption(specializationSelect, 'Neurology', 'Neurology');
            addOption(specializationSelect, 'Pediatrics', 'Pediatrics');
        }
    } else {
        specializationContainer.style.display = 'none';
    }
});

function addOption(selectElement, text, value) {
    const option = document.createElement('option');
    option.text = text;
    option.value = value;
    selectElement.add(option);
}
