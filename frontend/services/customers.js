var CustomersService = {
    
    // Method to get all customers from the backend API
    getCustomers: function() {
        return $.ajax({
            url: "http://localhost/exercise/backend/rest/customers",
            type: "GET",
            dataType: "json"
        });
    }
    
}

// Initialize customers page when it loads
$(document).on('spapp.page.loaded', function(event, data) {
    if (data.name === 'customers') {
        CustomersService.init();
    }
});

// Add init method to CustomersService
CustomersService.init = function() {
    console.log("Initializing customers page...");
    
    // Load customers into the dropdown
    CustomersService.loadCustomers();
    
    // Set up event listener for customer selection
    CustomersService.setupCustomerSelection();
};

CustomersService.loadCustomers = function() {
    console.log("Loading customers...");
    
    // Call the backend API to get customers
    CustomersService.getCustomers()
        .done(function(customers) {
            console.log("Customers loaded:", customers);
            
            // Get the select element
            var selectElement = $('#customers-list');
            
            // Clear existing options except the first one
            selectElement.find('option:not(:first)').remove();
            
            // Add each customer as an option
            customers.forEach(function(customer) {
                var option = $('<option></option>')
                    .attr('value', customer.id)
                    .text(customer.first_name + ' ' + customer.last_name);
                
                selectElement.append(option);
            });
        })
        .fail(function(xhr, status, error) {
            console.error("Error loading customers:", error);
            toastr.error("Failed to load customers");
        });
};

CustomersService.setupCustomerSelection = function() {
    // Listen for customer selection changes
    $('#customers-list').on('change', function() {
        var customerId = $(this).val();
        
        if (customerId && customerId !== 'Please select one customer') {
            console.log("Customer selected:", customerId);
            
        }
    });
};