# laravel-loan-crud
Laravel loan mini application

### How to start project
1. Copy `.env.example` to `.env`
2. Run `docker-compose up -d`
3. Generate application key
   1. Run `docker-compose exec app php artisan key:generate`
4. Run migration
   1. Run `docker-compose exec app php artisan migrate`
5. Go to `localhost:8000`

### Api Endpoint
1. Get all loans `GET /api/loans`
   1. Loan can filter by `loanAmountMin, loanAmountMax, loanTermMin, loanTermMax, interestMin, interestMax`
2. Create loan `POST /api/loans`
   1. Payload
   2. ```
      {
          loan_amount: 10000,
          loan_term: 1,
          interest_rate: 10,
          month: 1,
          year: 2017
      }
      ```
3. Update loan `PUT /api/loans/{id}`
   1. Payload
   2. ```
      {
          loan_amount: 10000,
          loan_term: 1,
          interest_rate: 10,
          month: 1,
          year: 2017
      }
      ```
4. Delete loan `DELETE /api/loans/{id}`