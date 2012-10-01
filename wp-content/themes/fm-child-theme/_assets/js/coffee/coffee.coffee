jQuery(($) ->
  (->

    # Set the polling interval
    poll_interval = 5000 # 5 seconds

    # Setup the timer
    setInterval (->

      # Get the JSON
      $.getJSON ajax_object.ajaxurl,
        action: "some_action"
      , (data) ->
        console.log data


    # Resize the graph percentages animate the width and background
    ), 3000
  )()
)(jQuery)