let display = document.getElementById("display");
        
        function appendValue(value) {
            if (value === '.' && display.value.includes('.')) return;
            display.value += value;
        }
        
        function clearDisplay() {
            display.value = "";
        }
        
        function calculateResult() {
            try {
                display.value = eval(display.value);
            } catch {
                display.value = "Error";
            }
        }
        
        document.addEventListener("keydown", function(event) {
            const key = event.key;
            if (!isNaN(key) || key === '.' || key === '+' || key === '-' || key === '*' || key === '/') {
                appendValue(key);
            } else if (key === 'Enter') {
                calculateResult();
            } else if (key === 'Backspace') {
                display.value = display.value.slice(0, -1);
            } else if (key === 'Escape') {
                clearDisplay();
            }
        });