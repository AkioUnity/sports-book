<?PHP
/**
 * Parser for validating XML
 * @package TestUtils
 * @author Brian Bischoff <brian@enetpulse.com>
 *
 */
class XMLParser{
  public $encoding = "UTF-8";
  public $write = false;
  public $subscriptionid;
  public $requestid;
  public $last_push;
  public $current_push;
  public $exec;
  public $debugstr = "DEBUG\n";
  public $all_attrs = array();
  public $all_elements = array();
  function parseFeed($xml){
    $this->_xml = xml_parser_create($this->encoding);
    xml_set_object($this->_xml, $this);
    xml_set_element_handler($this->_xml, "_parse_start_tags", "_parse_end_tags");
    xml_parser_set_option($this->_xml, XML_OPTION_CASE_FOLDING, 0);
    if(xml_parse($this->_xml,$xml,true)){
      xml_parser_free( $this->_xml );
      return array(0 => $this->all_elements, 1=> $this->all_attrs);
    }else{
      return false;
    }
  }

    public function reset()
    {
        $this->all_elements = array();
        $this->all_attrs = array();
    }
  function _parse_end_tags($a,$b){}
  function _parse_start_tags($parser, $element, $attrs){
    switch($element){
    case "subscription-update";
    $this->debugstr .= "subscription-update\n";
    $this->subscriptionid = $attrs["subscriptionid"];
    $this->requestid = $attrs["requestid"];
    $this->last_push = $attrs["last_push"];
    $this->current_push = $attrs["current_push"];
    $this->exec = $attrs["exec"];
    break;
    case "event_participant":
    case "standing_participant":
    case "object_participant":
    case "country":
    case "status_desc":
    case "result_type":
    case "incident_type":
    case "event_incident_type":
    case "event_incident_type_text":
    case "lineup_type":
    case "offence_type":
    case "standing_type":
    case "standing_type_param":
    case "standing_config":
    case "language_type":
    case "sport":
    case "participant":
    case "tournament_template":
    case "tournament":
    case "tournament_stage":
    case "event":
    case "event_participants":
    case "outcome":
    case "odds_provider":
    case "bettingoffer":
    case "object_participants":
    case "lineup":
    case "incident":
    case "event_incident":/**/
    case "event_incident_detail":
    case "result":
    case "standing":
    case "standing_participants":
    case "standing_data":
    case "property":
    case "language":
    case "image":
    case "reference":
    case "reference_type":
    case "scope_type":
    case "scope_data_type":
    case "event_scope":
    case "event_scope_detail":
    case "scope_result":
    case "lineup_scope_result":
    case "venue_data":
    case "venue_data_type":
    case "venue":
    case "venue_type":
    case "venue_object": 
    case "stats_live":
    case "stat_rules":
    case "stats_odds":
    case "liveodds_bettingoffer":
    case "liveodds_bettingoffer_average":
    case "liveodds_scope":
    case "liveodds_status":
    case "liveodds_subtype":
    case "liveodds_type":
    case "draw_type":
    case "draw":
    case "draw_config":
    case "draw_detail":
    case "draw_event":
    case "draw_event_detail":
    case "draw_event_participants":
    case "round_type":
    case "object_type":
    case "statistic":
    case "statistic_config":
    case "statistic_type":
    case "statistic_data_type":
    case "statistic_data_type_category":
    case "statistic_data_type_detail":
    case "statistic_participants1":
    case "statistic_data1":
    case "statistic_participants2":
    case "statistic_data2":
    case "statistic_participants3":
    case "statistic_data3":
    case "statistic_participants4":
    case "statistic_data4":
    case "statistic_participants5":
    case "statistic_data5":
    case "statistic_participants6":
    case "statistic_data6":
    case "statistic_participants7":
    case "statistic_data7":
    case "statistic_participants8":
    case "statistic_data8":    
    	
      if($element=="event_participant"){$element = "event_participants";}
    if($element=="standing_participant"){$element = "standing_participants";}
    if($element=="object_participant"){$element = "object_participants";}
    if($element=="draw_event_participant"){$element = "draw_event_participants";}

    /*echo "Element type $element has ".array_keys($attrs)." keys, values ".array_values($attrs)."\n";
      foreach($attrs as $key => $value){
      echo "$key:$value\n";
      }*/
    //$sql = "UPDATE $element(".implode(',',array_keys($attrs)).") VALUES('".implode("','", array_values($attrs))."')";
    foreach ($attrs as $key => $value) {
      if(!in_array($key,$this->attributes[$element]) && !in_array($key,$this->attr_in_all) ){
	unset($attrs[$key]);
      }
    }

    array_push($this->all_attrs, $attrs);
    array_push($this->all_elements, $element);
    //echo $sql;
    break;
    default:
      break;
    }
  }

  /*
   * Here we define the attributes from the XML files that should be included in the database
   * $attr_in_all => Attributes that should always be included
   * $attributes => List of tables, which each contain a list of attributes, remove or add
   * attributes here to have it inculded in the database (make sure it the field exists in the database when adding attributes)
   */
  public $attr_in_all = array("id","n","ut","del");
  public $attributes = array(
    "bettingoffer" => array(
      "outcomeFK","odds_providerFK","odds","odds_old","active","is_back","is_single","is_live","volume","currency","couponKey"
			    ),
    "country" => array(
      "name"
		       ),
    "event" => array(
      "name","tournament_stageFK","startdate","eventstatusFK","status_type","status_descFK"
		     ),
    "event_incident" => array(
      "eventFK","sportFK","event_incident_typeFK","elapsed","elapsed_plus","comment","sortorder"
			      ),
    "event_incident_detail" => array(
      "type","event_incidentFK","participantFK","value"
				     ),
    "event_incident_type" => array(
      "player1","player2","team","comment","subtype1","subtype2","name","type","comment_type","player2_type"
				   ),
    "event_incident_type_text" => array(
      "event_incident_typeFK","name"
					),
    "event_participants" => array(
      "number","participantFK","eventFK"
				  ),
    "image" => array(
      "object","objectFK","type","contenttype","name","value"
		     ),
    "incident" => array(
      "event_participantsFK","incident_typeFK","incident_code","elapsed","sortorder","ref_participantFK"
			),
    "incident_type" => array(
      "name","subtype"
			     ),
    "language" => array(
      "object","objectFK","language_typeFK","name"
			),
    "language_type" => array(
      "name","description"
			     ),
    "lineup" => array(
      "event_participantsFK","participantFK","lineup_typeFK","shirt_number","pos"
		      ),
    "lineup_type" => array(
      "name"
			   ),
    "object_participants" => array(
      "object","objectFK","participantFK","participant_type","date_from","date_to","active"
				   ),
    "offence_type" => array(
      "name"
			    ),
    "odds_provider" => array(
      "name","url","countryFK","bookmaker","preferred","betex","active"
			     ),
    "outcome" => array(
      "object","objectFK","type","event_participant_number","scope","subtype","iparam","iparam2","dparam","dparam2","sparam"
		       ),
    "participant" => array(
      "name","gender","type","countryFK"
			   ),
    "property" => array(
      "object","objectFK","type","name","value"
			),
    "reference" => array(
      "object","objectFK","refers_to","name", "reference_typeFK"
			 ),
    "reference_type" => array(
      "name","description"
			      ),
    "result" => array(
      "event_participantsFK","result_typeFK","result_code","value"
		      ),
    "result_type" => array(
      "name","code"
			   ),
    "sport" => array(
      "name"
		     ) ,
    "standing" => array(
      "object","objectFK","standing_typeFK","name"
			),
    "standing_config" => array(
      "standingFK","standing_type_paramFK","value","sub_param"
			       ),
    "standing_data" => array(
      "standing_participantsFK","standing_type_paramFK","value","code","sub_param"
			     ),
    "standing_participants" => array(
      "standingFK","participantFK","rank"
				     ),
    "standing_type" => array(
      "name","description"
			     ),
    "standing_type_param" => array(
      "standing_typeFK","code","name","type","value"
				   ),
    "status_desc" => array(
      "name","status_type"
			   ),
    "tournament" => array(
      "name","tournament_templateFK"
			  ),
    "tournament_stage" => array(
      "name","tournamentFK","gender","countryFK","startdate","enddate"
				),
    "tournament_template" => array(
      "name","sportFK","gender"
				   ),
    "scope_type" => array(
      "name","description"
			  ),
    "scope_data_type" => array(
      "name","description"  
			       ),			     			     
    "event_scope" => array(
      "eventFK", "scope_typeFK"
			   ),	
    "event_scope_detail" => array(
      "event_scopeFK", "name", "value"
				  ),							     
    "scope_result" => array(
      "event_participantsFK", "event_scopeFK", "scope_data_typeFK", "value"
			    ),	
    "lineup_scope_result" => array(
      "lineupFK", "event_scopeFK", "scope_data_typeFK", "value"
				   ),
    "venue_data" => array(
      "value","venue_data_typeFK", "venueFK"
			  ),
    "venue_data_type" => array(
      "name"
			       ),
    "venue" => array(
      "name", "countryFK", "venue_typeFK"
		     ),
    "venue_type" => array(
      "name"
			  ),
    "stats_live" => array(
      "stat_rulesFK","participantFK","eventFK","teamFK","trigger_type","matchcount","value","value_div_matchcount","incidentFK","outcomeFK"
			  ),
    "stats_odds" => array(
      "stat_rulesFK","param","name","type"
			  ),
    "stat_rules" => array(
      "rule","rule_type","object_type","homeaway","matchrange","h2h","sub_param","name","type"
			  ),
    "liveodds_bettingoffer" => array(
      "object", "objectFK", "odds_providerFK", "liveodds_typeFK", "liveodds_subtypeFK", "liveodds_scopeFK", "liveodds_statusFK", "iparam1", "iparam2", "dparam1", "dparam2", "sparam", "value", "value_old"
				     ),
    "liveodds_bettingoffer_average" => array(
      "object", "objectFK", "liveodds_typeFK", "liveodds_subtypeFK", "liveodds_scopeFK", "liveodds_statusFK", "iparam1", "iparam2", "dparam1", "dparam2", "sparam", "value", "value_old"
					     ),			      			
    "liveodds_scope" => array(
      "name","description"  
			      ),			     			     
    "liveodds_status" => array(
      "name","description"  
			       ),			     			     
    "liveodds_subtype" => array(
      "name","description"  
				),			     			     
    "liveodds_type" => array(
      "name","description"  
			     ),
    "round_type" => array(
      "name","value","knockout"
			  ),
    "object_type" => array(
      "name","description"
			   ),
    "venue_object" => array(
      "object_typeFK","objectFK","venueFK","neutral"
			    ),
    "draw_type" => array(
      "name","description"
			 ),
    "draw" =>  array(
      "name","object_typeFK","objectFK","draw_typeFK"
		     ),
    "draw_config" =>  array(
      "name","drawFK","value"
			    ),
    "draw_detail" =>  array(
      "drawFK","participantFK","rank","value"
			    ),
    "draw_event" =>  array(
      "name","drawFK","round_typeFK","draw_eventFK","draw_order"
			   ),
    "draw_event_detail"  =>  array(
      "draw_eventFK","object_typeFK","objectFK","startdate","rank","draw_event_detailFK"
				   ),
    "draw_event_participants" =>  array(
      "draw_eventFK","participantFK","number"
					),
    "statistic" => array(
      "object_typeFK","objectFK","statistic_typeFK","name"
			 ),
    "statistic_config" => array(
      "statisticFK","statistic_data_typeFK"
				),
    "statistic_data1" => array(
      "statistic_participants1FK","statistic_data_typeFK","statistic_data_type_detailFK","value"
			       ),
    "statistic_participants1" => array(
      "statisticFK","participantFK"
				       ),
    "statistic_data2" => array(
      "statistic_participants2FK","statistic_data_typeFK","statistic_data_type_detailFK","value"
			       ),
    "statistic_participants2" => array(
      "statisticFK","participantFK"
				       ),
    "statistic_data3" => array(
      "statistic_participants3FK","statistic_data_typeFK","statistic_data_type_detailFK","value"
			       ),
    "statistic_participants3" => array(
      "statisticFK","participantFK"
				       ),
    "statistic_data4" => array(
      "statistic_participants4FK","statistic_data_typeFK","statistic_data_type_detailFK","value"
			       ),
    "statistic_participants4" => array(
      "statisticFK","participantFK"
				       ),
    "statistic_data5" => array(
      "statistic_participants5FK","statistic_data_typeFK","statistic_data_type_detailFK","value"
			       ),
    "statistic_participants5" => array(
      "statisticFK","participantFK"
				       ),
    "statistic_data6" => array(
      "statistic_participants6FK","statistic_data_typeFK","statistic_data_type_detailFK","value"
			       ),
    "statistic_participants6" => array(
      "statisticFK","participantFK"
				       ),
    "statistic_data7" => array(
      "statistic_participants7FK","statistic_data_typeFK","statistic_data_type_detailFK","value"
			       ),
    "statistic_participants7" => array(
      "statisticFK","participantFK"
				       ),
    "statistic_data8" => array(
      "statistic_participants8FK","statistic_data_typeFK","statistic_data_type_detailFK","value"
			       ),
    "statistic_participants8" => array(
      "statisticFK","participantFK"
				       ),
    "statistic_data_type" => array(
      "name","statistic_typeFK","statistic_data_type_categoryFK","code","description"
				   ),
    "statistic_data_type_detail" => array(
      "statistic_data_typeFK","name","description"
					  ),
    "statistic_data_type_category" => array(
      "name","description"
					    ),
    "statistic_type" => array(
      "name","description"
			      )

			     );
}