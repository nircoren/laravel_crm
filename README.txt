Approach:
    - We will display the calls in a grid, joining related Customer and Agent data.
    - Relations between tables are as follows:
        - Customer to calls: 1 to many
        - Agent to calls: 1 to many
        - Customer to Agent: 1 to many
    - We display the grid in Blade view.
    - View gets the through CallController
    - Filters are passed in the following format:
        filter[modelName][field] = value
        in query param:
        ?filter[Customer][name]=John&filter[Agent][id]=2

Assumptions:
    - Currently, we don't need any many-to-many relationship between the tables.
      I assumed Customer can be assigned to only one Agent, and call can be assigned to only one Customer and Agent.
      If it wasn't the case, we would have to create a pivot table for many-to-many relationships.
    - I know its bad that page refreshes with each request!!
        I left it like that because the task is supposed to be short. would've used AJAX  or Laravel wire otherwise.

Steps taken:
    - understanding the task.
    - thinking about relations between models.
    - figure out the approach, how the data flows.
    - create models, controllers, migrations.
    - create relations between models.
    - create the routes
    - create views: grid and filters.
    - create dummy data.
    - clean up the code.

Ways of increasing the performance:
    - indexing: id is indexed by default in laravel. Also indexed create_at
    - pagination: using pagination to separate the queries. right now it doesnt seem effective because the number of calls is very low.
    - caching: added cache to make repeated requests faster.
    - pagination: could add frontend pagination
    - lazy loading: could use lazy loading to load the data in chunks.
