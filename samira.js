// ============================================
// DATA STORAGE WITH LOCALSTORAGE
// ============================================

// Load data from localStorage or initialize empty arrays
function _loadData() {
    try {
        const companiesData = localStorage.getItem('companies');
        const irrigationDataStorage = localStorage.getItem('irrigationData');
        const farmerDataStorage = localStorage.getItem('farmerData');
        
        return {
            companies: companiesData ? JSON.parse(companiesData) : [],
            irrigationData: irrigationDataStorage ? JSON.parse(irrigationDataStorage) : [],
            farmerData: farmerDataStorage ? JSON.parse(farmerDataStorage) : []
        };
    } catch (e) {
        console.error("Error loading data:", e);
        return {
            companies: [],
            irrigationData: [],
            farmerData: []
        };
    }
}

// Save data to localStorage
function _saveData() {
    try {
        localStorage.setItem('companies', JSON.stringify(companies));
        localStorage.setItem('irrigationData', JSON.stringify(irrigationData));
        localStorage.setItem('farmerData', JSON.stringify(farmerData));
    } catch (e) {
        console.error("Error saving data:", e);
    }
}

// Initialize data from localStorage
let data = _loadData();
let companies = data.companies;
let irrigationData = data.irrigationData;
let farmerData = data.farmerData;

// ============================================
// COMPANY FUNCTIONS
// ============================================
function _companyReg() {
    var nameofCompany = prompt("What is the company name?", "");
    _addFarmerCompany(nameofCompany);
}

function _addFarmerCompany(name) {
    if (name && name.trim()) {
        companies.push({
            name: name,
            registeredDate: new Date().toLocaleDateString()
        });
        _saveData();
        alert(name + " added successfully!");
        _updateAnalytics();
    } else {
        alert("Company name cannot be empty!");
    }
}

function _irrgationData() {
    if (companies.length === 0) {
        alert("Please register a company first!");
        return;
    }
    
    var companyName = prompt("Enter company name:", companies[companies.length - 1].name);
    var irri = prompt("Write the irrigation usage in m³/min:", "");
    var farmLocation = prompt("Enter farm location:", "");
    
    _irrigationSec(companyName, irri, farmLocation);
}

function _irrigationSec(companyName, irrigation, location) {
    if (irrigation && irrigation.trim() && !isNaN(irrigation)) {
        irrigationData.push({
            company: companyName,
            amount: parseFloat(irrigation),
            location: location,
            date: new Date().toLocaleDateString(),
            time: new Date().toLocaleTimeString()
        });
        _saveData();
        alert(irrigation + " m³/min added successfully for " + companyName + "!");
        _updateAnalytics();
    } else {
        alert("Irrigation data cannot be empty or invalid!");
    }
}

function _viewReports() {
    let report = "📋 Company Reports:\n\n";

    if (companies.length === 0) {
        report += "No companies registered yet.\n";
    } else {
        report += "Registered Companies:\n";
        companies.forEach((c, i) => {
            report += (i + 1) + ". " + c.name + " (Registered: " + c.registeredDate + ")\n";
        });
    }

    report += "\n";

    if (irrigationData.length === 0) {
        report += "No irrigation data uploaded yet.\n";
    } else {
        report += "Irrigation Data:\n";
        irrigationData.forEach((ir, i) => {
            report += (i + 1) + ". Company: " + ir.company + 
                     " | Amount: " + ir.amount + " m³/min" +
                     " | Location: " + ir.location +
                     " | Date: " + ir.date + "\n";
        });
    }

    alert(report);
}

// ============================================
// FARMER FUNCTIONS
// ============================================
function _enterFarmerData() {
    var farmerName = prompt("Enter your name:", "");
    if (!farmerName || !farmerName.trim()) {
        alert("Name cannot be empty!");
        return;
    }
    
    var waterUsage = prompt("Enter today's water usage (in Liters):", "");
    if (!waterUsage || isNaN(waterUsage)) {
        alert("Please enter a valid number for water usage!");
        return;
    }
    
    var farmSize = prompt("Enter farm size (in hectares):", "1");
    
    farmerData.push({
        name: farmerName.trim(),
        waterUsage: parseFloat(waterUsage),
        farmSize: parseFloat(farmSize) || 1,
        date: new Date().toLocaleDateString(),
        time: new Date().toLocaleTimeString()
    });
    
    _saveData();
    alert("Data added successfully for " + farmerName + "!");
    _updateAnalytics();
    _updateFarmerDashboard();
}

function _updateFarmDetails() {
    var farmerName = prompt("Enter your name:", "");
    var cropType = prompt("What crops are you growing?", "");
    var irrigationType = prompt("Irrigation type (Drip/Sprinkler/Flood):", "");
    
    alert("Farm details updated for " + farmerName + "!\nCrop: " + cropType + "\nIrrigation: " + irrigationType);
}

function _viewFarmerHistory() {
    if (farmerData.length === 0) {
        alert("No farmer data recorded yet!");
        return;
    }
    
    let history = "📊 FARMER WATER USAGE HISTORY\n";
    history += "=".repeat(50) + "\n\n";
    
    farmerData.forEach((farmer, i) => {
        history += (i + 1) + ". " + farmer.name + "\n";
        history += "   Water Used: " + farmer.waterUsage + " L\n";
        history += "   Farm Size: " + farmer.farmSize + " hectares\n";
        history += "   Date: " + farmer.date + " at " + farmer.time + "\n";
        
        if (farmer.waterUsage > 300) {
            history += "   ⚠️ WARNING: Exceeds recommended limit!\n";
        }
        history += "\n";
    });
    
    let totalWater = farmerData.reduce((sum, f) => sum + f.waterUsage, 0);
    let avgWater = (totalWater / farmerData.length).toFixed(2);
    
    history += "=".repeat(50) + "\n";
    history += "Total Entries: " + farmerData.length + "\n";
    history += "Total Water Used: " + totalWater.toFixed(2) + " L\n";
    history += "Average per Entry: " + avgWater + " L\n";
    
    alert(history);
}

function _updateFarmerDashboard() {
    if (farmerData.length > 0) {
        let todayTotal = farmerData.reduce((sum, f) => sum + f.waterUsage, 0);
        let todayElement = document.getElementById('todayWaterUse');
        if (todayElement) {
            todayElement.textContent = todayTotal.toFixed(2) + ' L';
        }
        
        let weeklyAvg = (todayTotal / farmerData.length).toFixed(2);
        let weeklyElement = document.getElementById('weeklyAverage');
        if (weeklyElement) {
            weeklyElement.textContent = weeklyAvg + ' L';
        }
        
        let alerts = farmerData.filter(f => f.waterUsage > 300).length;
        let alertElement = document.getElementById('farmerAlerts');
        if (alertElement) {
            alertElement.textContent = alerts;
        }
    }
}

// ============================================
// ANALYTICS FUNCTIONS
// ============================================
function _updateAnalytics() {
    // Update Average Water Consumption
    if (farmerData.length > 0) {
        let totalWater = farmerData.reduce((sum, farmer) => sum + farmer.waterUsage, 0);
        let avgWater = (totalWater / farmerData.length).toFixed(2);
        
        var avgElement = document.getElementById("Average Water Consumption");
        if (avgElement) {
            avgElement.textContent = avgWater + " L/day";
        }
    } else {
        var avgElement = document.getElementById("Average Water Consumption");
        if (avgElement) {
            avgElement.textContent = "0 L/day";
        }
    }
    
    // Update Alerts (based on threshold)
    let excessCount = 0;
    farmerData.forEach(farmer => {
        if (farmer.waterUsage > 300) { // Threshold: 300L
            excessCount++;
        }
    });
    
    irrigationData.forEach(irr => {
        if (irr.amount > 5) { // Threshold: 5 m³/min
            excessCount++;
        }
    });
    
    var alertElement = document.getElementById("Excess Consumption Alerts");
    if (alertElement) {
        alertElement.textContent = excessCount + " Alerts";
    }
    
    // Calculate Water Saved Percentage
    if (farmerData.length > 0) {
        let totalWaterUsed = farmerData.reduce((sum, f) => sum + f.waterUsage, 0);
        let potentialWaste = farmerData.length * 50; // Assume 50L could be saved per farm
        let waterSavedPercent = ((potentialWaste / totalWaterUsed) * 100).toFixed(1);
        
        var savedElement = document.getElementById("Water Saved");
        if (savedElement) {
            savedElement.textContent = waterSavedPercent + "%";
        }
    }
    
    // Update display message
    _showUpdateMessage();
}

function _showUpdateMessage() {
    var messageDiv = document.querySelector('.fixed-alert label');
    if (messageDiv) {
        let totalEntries = companies.length + farmerData.length + irrigationData.length;
        messageDiv.textContent = "Alert-messages: " + totalEntries + " total data entries recorded!";
    }
}

function _generateDetailedReport() {
    let report = "📊 DETAILED ANALYTICS REPORT\n";
    report += "=".repeat(50) + "\n\n";
    
    // Companies Summary
    report += "🏢 COMPANIES (" + companies.length + " registered)\n";
    companies.forEach((c, i) => {
        report += "  " + (i + 1) + ". " + c.name + " - " + c.registeredDate + "\n";
    });
    
    report += "\n💧 IRRIGATION DATA (" + irrigationData.length + " entries)\n";
    if (irrigationData.length > 0) {
        let totalIrrigation = irrigationData.reduce((sum, ir) => sum + ir.amount, 0);
        report += "  Total Irrigation: " + totalIrrigation.toFixed(2) + " m³/min\n";
    }
    
    report += "\n🌾 FARMER DATA (" + farmerData.length + " entries)\n";
    if (farmerData.length > 0) {
        let totalFarmerWater = farmerData.reduce((sum, f) => sum + f.waterUsage, 0);
        report += "  Total Water Usage: " + totalFarmerWater.toFixed(2) + " Liters\n";
        let avgWater = totalFarmerWater / farmerData.length;
        report += "  Average per Farm: " + avgWater.toFixed(2) + " L/day\n";
    }
    
    report += "\n" + "=".repeat(50);
    alert(report);
}

// Clear all data (useful for testing)
function _clearAllData() {
    if (confirm("Are you sure you want to clear ALL data? This cannot be undone!")) {
        localStorage.clear();
        companies = [];
        irrigationData = [];
        farmerData = [];
        alert("All data cleared!");
        _updateAnalytics();
        _updateFarmerDashboard();
    }
}

// ============================================
// INITIALIZE ON PAGE LOAD
// ============================================
window.addEventListener('DOMContentLoaded', function() {
    // Reload data from localStorage when page loads
    let data = _loadData();
    companies = data.companies;
    irrigationData = data.irrigationData;
    farmerData = data.farmerData;
    
    // Update all displays
    _updateAnalytics();
    _updateFarmerDashboard();
    
    console.log("Data loaded:", {
        companies: companies.length,
        irrigationData: irrigationData.length,
        farmerData: farmerData.length
    });
});


// Function to calculate bill - Meets Project Requirement 4 [cite: 100]
    function calculateBill() {
        // 1. Get values
        let liters = parseFloat(document.getElementById('waterInput').value);
        let isLargeFarm = document.getElementById('largeFarm').checked;
        let hasSmartTech = document.getElementById('smartTech').checked;
        
        // Validation
        if (isNaN(liters) || liters < 0) {
            alert("Please enter a valid water amount.");
            return;
        }

        // 2. Define Rates (Logic Requirement) 
        // Small farms pay 0.002 per liter, Companies pay 0.005
        let rate = 0;
        if (isLargeFarm) {
            rate = 0.005; 
        } else {
            rate = 0.002;
        }

        // 3. Perform Math Calculation 
        let totalCost = liters * rate;

        // 4. Apply Discount Condition (Logic Requirement) 
        let message = "";
        if (hasSmartTech) {
            let discountAmount = totalCost * 0.15; // 15% discount
            totalCost = totalCost - discountAmount; 
            message = "🌱 Sustainability Subsidy Applied (15% Savings!)";
        } else {
            message = "💡 Tip: Switch to smart irrigation to save 15%.";
        }

        // 5. Display Result
        document.getElementById('finalCost').innerText = totalCost.toFixed(2);
        document.getElementById('discountMsg').innerText = message;
        document.getElementById('resultBox').style.display = 'block';
    }
