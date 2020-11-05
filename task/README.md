# Test project

Build a service that sets and list information about who is on call duty. Choose any language/framework you're comfortable with.

## Goals

- See how you design, test and document your code
- Ability to figure out high level instructions and defend your design choices


## Requirements

Given the CSV file in this project that represents the existing users, build an API having:

- An endpoint to list users who are on call
- An endpoint to set oncall for an user, given an username, where:
  - only users with valid mobile numbers can be set on call
  - There should be always a minimum of 2 users on call
  - After a successful oncall set, an API to a third party service that will send an SMS message to this user's number should be called (you can mock this 3rd part dependency)
  - A non-german or blank mobile number are considered invalid
  - Records the date and time when the on call was set/unset
  - A comment can be added, but it is optional
  - All the history of oncall updates is recorded


Also, you should provide documentation on how to run the application/tests
