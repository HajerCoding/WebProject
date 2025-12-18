// ==============================================
// SIMPLIFIED JAVASCRIPT VALIDATION
// For COMP3700 Fall 2025 Project Part 4
// ==============================================

// ==============================================
// VALIDATION FUNCTION 1: Farmer Water Usage Form
// ==============================================
function validateFarmerForm() {
    // Get form elements
    var farmerName = document.getElementById('farmerName');
    var waterUsage = document.getElementById('waterUsage');
    var farmSize = document.getElementById('farmSize');
    var entryDate = document.getElementById('entryDate');
    
    var isValid = true;
    var errorMessage = "";
    
    // 1. Required field check - Farmer Name
    if (!farmerName || farmerName.value.trim() === "") {
        errorMessage += "• Farmer name is required\n";
        isValid = false;
        if (farmerName) {
            farmerName.style.borderColor = "red";
        }
    } else if (farmerName.value.trim().length < 3) {
        errorMessage += "• Farmer name must be at least 3 characters\n";
        isValid = false;
        farmerName.style.borderColor = "red";
    } else {
        farmerName.style.borderColor = "green";
    }
    
    // 2. Range validation - Water Usage
    if (!waterUsage || waterUsage.value === "") {
        errorMessage += "• Water usage is required\n";
        isValid = false;
        if (waterUsage) {
            waterUsage.style.borderColor = "red";
        }
    } else {
        var waterValue = parseFloat(waterUsage.value);
        if (isNaN(waterValue) || waterValue < 0) {
            errorMessage += "• Water usage must be a positive number\n";
            isValid = false;
            waterUsage.style.borderColor = "red";
        } else if (waterValue > 1000) {
            errorMessage += "• Water usage seems too high (max 1000 L)\n";
            isValid = false;
            waterUsage.style.borderColor = "red";
        } else {
            waterUsage.style.borderColor = "green";
        }
    }
    
    // 3. Range validation - Farm Size
    if (!farmSize || farmSize.value === "") {
        errorMessage += "• Farm size is required\n";
        isValid = false;
        if (farmSize) {
            farmSize.style.borderColor = "red";
        }
    } else {
        var sizeValue = parseFloat(farmSize.value);
        if (isNaN(sizeValue) || sizeValue <= 0) {
            errorMessage += "• Farm size must be greater than 0\n";
            isValid = false;
            farmSize.style.borderColor = "red";
        } else {
            farmSize.style.borderColor = "green";
        }
    }
    
    // 4. Required field check - Date
    if (!entryDate || entryDate.value === "") {
        errorMessage += "• Entry date is required\n";
        isValid = false;
        if (entryDate) {
            entryDate.style.borderColor = "red";
        }
    } else {
        entryDate.style.borderColor = "green";
    }
    
    // Display error message or submit
    if (!isValid) {
        alert("Please fix the following errors:\n\n" + errorMessage);
        return false;
    }
    
    return true;
}

// ==============================================
// VALIDATION FUNCTION 2: Company Irrigation Form
// ==============================================
function validateIrrigationForm() {
    // Get form elements
    var companyName = document.getElementById('companyName');
    var irrigationAmount = document.getElementById('irrigationAmount');
    var location = document.getElementById('location');
    var recordDate = document.getElementById('recordDate');
    
    var isValid = true;
    var errorMessage = "";
    
    // 1. Required field check + Length validation - Company Name
    if (!companyName || companyName.value.trim() === "") {
        errorMessage += "• Company name is required\n";
        isValid = false;
        if (companyName) {
            companyName.style.borderColor = "red";
        }
    } else if (companyName.value.trim().length < 3) {
        errorMessage += "• Company name must be at least 3 characters\n";
        isValid = false;
        companyName.style.borderColor = "red";
    } else if (companyName.value.trim().length > 100) {
        errorMessage += "• Company name must be less than 100 characters\n";
        isValid = false;
        companyName.style.borderColor = "red";
    } else {
        companyName.style.borderColor = "green";
    }
    
    // 2. Range validation - Irrigation Amount
    if (!irrigationAmount || irrigationAmount.value === "") {
        errorMessage += "• Irrigation amount is required\n";
        isValid = false;
        if (irrigationAmount) {
            irrigationAmount.style.borderColor = "red";
        }
    } else {
        var amount = parseFloat(irrigationAmount.value);
        if (isNaN(amount) || amount <= 0) {
            errorMessage += "• Irrigation amount must be greater than 0\n";
            isValid = false;
            irrigationAmount.style.borderColor = "red";
        } else if (amount > 100) {
            errorMessage += "• Irrigation amount seems too high (max 100 m³/min)\n";
            isValid = false;
            irrigationAmount.style.borderColor = "red";
        } else {
            irrigationAmount.style.borderColor = "green";
        }
    }
    
    // 3. Required field check - Location
    if (!location || location.value.trim() === "") {
        errorMessage += "• Location is required\n";
        isValid = false;
        if (location) {
            location.style.borderColor = "red";
        }
    } else if (location.value.trim().length < 3) {
        errorMessage += "• Location must be at least 3 characters\n";
        isValid = false;
        location.style.borderColor = "red";
    } else {
        location.style.borderColor = "green";
    }
    
    // 4. Required field check - Date
    if (!recordDate || recordDate.value === "") {
        errorMessage += "• Record date is required\n";
        isValid = false;
        if (recordDate) {
            recordDate.style.borderColor = "red";
        }
    } else {
        recordDate.style.borderColor = "green";
    }
    
    // Display error message or submit
    if (!isValid) {
        alert("Please fix the following errors:\n\n" + errorMessage);
        return false;
    }
    
    return true;
}

// ==============================================
// HELPER FUNCTIONS
// ==============================================

// Clear error styling when user starts typing
function clearErrorStyling(elementId) {
    var element = document.getElementById(elementId);
    if (element) {
        element.style.borderColor = "";
    }
}

// Add event listeners to clear errors on input
document.addEventListener('DOMContentLoaded', function() {
    // For Farmer Form
    var farmerInputs = ['farmerName', 'waterUsage', 'farmSize', 'entryDate'];
    farmerInputs.forEach(function(id) {
        var element = document.getElementById(id);
        if (element) {
            element.addEventListener('input', function() {
                clearErrorStyling(id);
            });
        }
    });
    
    // For Irrigation Form
    var irrigationInputs = ['companyName', 'irrigationAmount', 'location', 'recordDate'];
    irrigationInputs.forEach(function(id) {
        var element = document.getElementById(id);
        if (element) {
            element.addEventListener('input', function() {
                clearErrorStyling(id);
            });
        }
    });
});


function validateGovDB() {
    var form = document.forms["GovDBForm"];
    var region = form["region"].value.trim();
    var jan = form["jan"].value;
    var feb = form["feb"].value;
    var mar = form["mar"].value;

    if (region === "") {
        alert("Please enter a region name.");
        return false;
    }
    if (jan === "" || jan <= 0) {
        alert("Please enter a positive value for January.");
        return false;
    }
    // ... similar for feb and mar
    return true;
}
