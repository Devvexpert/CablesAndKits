<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex w-96">
                    <!-- Sender Section -->
                    <div class="w-1/2 pr-4 w-64" style="margin-left: 10px;">
                        <h3 class="text-lg font-semibold mb-4">Sender</h3>
                        <form id="sendMessageForm">
                            <!-- Recipient List -->
                            <div class="flex items-center mb-4">
                                <label for="recipient" class="mr-2">Recipient:</label>
                                <select id="recipient" name="recipient" class="border border-gray-300 rounded-md py-1">
                                    <!-- Options for recipients -->
                                    @foreach ($recipientList as $recipient)
                                        <option value="{{ $recipient->id }}">{{ $recipient->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Message Input -->
                            <div class="mb-4">
                                <label for="message" class="block">Message:</label>
                                <textarea id="message" name="message" rows="4" class="w-full border border-gray-300 rounded-md px-2 py-1" required></textarea>
                            </div>
                            <!-- Message Type -->
                            <div class="flex items-center">
                                <label for="expiry_type" class="mr-2">Message Type:</label>
                                <select id="expiry_type" name="msgtype" class="border border-gray-300 rounded-md px-2 py-1">
                                    <option value="1">Read once</option>
                                    <option value="2">Delete after 10 Min</option>
                                </select>
                            </div>
                            <div class="send-button">
                                <button type="button" id="sendButton" class="px-4 py-2 rounded" style="background: #00bfff;">Send</button>
                            </div>
                        </form>
                    </div>

                    <!-- Receiver Section -->
                    <div class="w-1/2 pl-4" style="margin-left: 50px;">
                        <h3 class="text-lg font-semibold mb-4">Receiver</h3>
                        <!-- Add Receiver Section Content Here -->
                        <ul id="receiverMessages" class="receive-message"></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
    /* Style for the message list */
    #receiverMessages {
        list-style-type: none; /* Remove bullet points */
        padding: 0;
    }

    /* Style for each message item */
    #receiverMessages li {
        background-color: #f0f0f0; /* Light gray background */
        padding: 10px;
        margin-bottom: 5px;
        border-radius: 5px;
    }

    /* Style for the message content */
    .message-content {
        font-weight: bold;
    }

    /* Style for the message timestamp */
    .message-timestamp {
        color: #666; /* Dark gray color */
        font-size: 0.8em; /* Small font size */
    }
    .send-button{
        justify-content: right;
        display: flex;
        margin-top: 20px;
    }
    .receive-message {
        height: 350px;
        overflow: auto;
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.24.0/axios.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    // Add event listener to the "Send" button
    document.getElementById('sendButton').addEventListener('click', function() {
        // Prevent default form submission
        event.preventDefault();

        // Get form data
        var formData = new FormData(document.getElementById('sendMessageForm'));

        // Send AJAX request
        axios.post('/send-message', formData)
            .then(function(response) {
                if(response && response.status == 200){
                    document.getElementById('sendMessageForm').reset();
                } else {
                    alert("something went wrong");

                }
            })
            .catch(function(error) {
                if (error && error.response.status == 422) {
                    // Validation errors received, update UI with error messages
                    var errors = error.response.data.errors;
                    var errorMessage = errors.message[0]; // Get the error message for the 'message' field
                    // Display the error message
                    alert(errorMessage);
                } else {
                    // Other types of errors
                    alert('An error occurred while submitting the form. Please try again.');
                }
            });
    });
    
</script>
<script>
    // Fetch messages for the receiver list
    $(document).ready(function() {
        function fetchMessages() {
            $.ajax({
                url: "/fetch-message",
                type: "GET",
                success: function(response) {
                    // Display the decrypted message
                    if(response && response.status == 200) {
                        console.log("response",response)
                        if (response.messages) {
                        // Clear existing messages
                            $('#receiverMessages').empty();
                            // Loop through received messages and display them
                            response.messages.forEach(function(message) {
                                // Append message to the receiverMessages list
                                $('#receiverMessages').append('<li>' + message.message + ' :' + message.created_at + '</li>');
                            });
                        }  
                    }
                                  },
                error: function(xhr, status, error) {
                    alert('An error occurred while fetching the message. Please try again.');
                }
            });
        };
        
    fetchMessages();

    setInterval(fetchMessages, 5000);
    });


</script>
