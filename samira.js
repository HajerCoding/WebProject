// Farmer object class
class Farmer {
    constructor(id, name, waterUsage, farmSize, date) {
        this.id = id;
        this.name = name;
        this.waterUsage = waterUsage;
        this.farmSize = farmSize;
        this.date = date;
    }
}

// Company object class
class Company {
    constructor(id, name, regDate, email, totalFarms) {
        this.id = id;
        this.name = name;
        this.registrationDate = regDate;
        this.contactEmail = email;
        this.totalFarms = totalFarms;
    }
}

// Irrigation Record object class
class IrrigationRecord {
    constructor(id, company, amount, location, date) {
        this.id = id;
        this.companyName = company;
        this.amount = amount;
        this.location = location;
        this.recordDate = date;
    }
}
