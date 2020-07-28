
# xenforo-advanced-api  
  
**Endpoints:** _users/_, _threads/_

**Features:** 

    filter=column_name=value
    search=value
    orderby=column,direction
    limit=1

**Example:** 

    ...api/users/?filter=user_id=>1;user_id<10
    ...api/users/?filter=username~min
    ...api/threads/?search=best
    ...api/threads/?orderby=user_id,desc
    ...api/threads/?limit=1&offset=1

