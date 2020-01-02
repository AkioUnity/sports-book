<?php

App::uses('CasinoAppController', 'Casino.Controller');

class GamesController extends CasinoAppController
{
    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Games';

    /**
     * An array containing the class names of models this controller uses.
     *
     * @var array A single name as a string or a list of names as an array.
     */
    public $uses = array('Casino.GameSetting', 'Casino.GameLog', 'User');

	/**
     * Components
     *
     * @var array
     */
    public $components = array(
        0   =>  'Utils',
        1   =>  'Session',
        2   =>  'RequestHandler'
    );

	private $redis = null;

	/**
     * Game constants
     *
     */
	const BLACK_JACK_STATE_DEAL_DEALER = 'DEAL_DEALER';
	const BLACK_JACK_STATE_DEAL_PLAYER = 'DEAL_PLAYER';
	const BLACK_JACK_FINISHING = 'FINISH';
	const STUD_ROYAL_FLUSH     = 0;
	const STUD_STRAIGHT_FLUSH  = 1;
	const STUD_FOUR_OF_A_KIND  = 2;
	const STUD_FULL_HOUSE      = 3;
	const STUD_FLUSH           = 4;
	const STUD_STRAIGHT        = 5;
	const STUD_THREE_OF_A_KIND = 6;
	const STUD_TWO_PAIR        = 7;
	const STUD_ONE_PAIR        = 8;
	const STUD_HIGH_CARD       = 9;
	const STUD_NO_HAND         = 10;
	const STUD_CARD_TWO 	   = 2;
	const STUD_CARD_THREE 	   = 3;
	const STUD_CARD_FOUR	   = 4;
	const STUD_CARD_FIVE 	   = 5;
	const STUD_CARD_SIX 	   = 6;
	const STUD_CARD_SEVEN 	   = 7;
	const STUD_CARD_EIGHT 	   = 8;
	const STUD_CARD_NINE 	   = 9;
	const STUD_CARD_TEN 	   = 10;
	const STUD_CARD_JACK 	   = 11;
	const STUD_CARD_QUEEN 	   = 12;
	const STUD_CARD_KING	   = 13;
	const STUD_CARD_ACE 	   = 14;
	const SLOT_BONUS_SYMBOL    = 9;
	const SLOT_WILD_SYMBOL     = 10;

	/**
     * Game flag names
     *
     */
	const BLACK_JACK_INSURANCE = 'INSURANCE';
	const BLACK_JACK_DOUBLE = 'DOUBLE';
	const BLACK_JACK_SPLIT = 'SPLIT';
	const BLACK_JACK_SPLIT_DONE = 'SPLIT_DONE';

	/**
     * Called before the controller action.
     *
     * @return void
     */
    public function beforeFilter()
    {
        if (!$this->request->is('ajax')) {
			throw new Exception('Not found!', 404);
        }
    }

	/**
     * Function to connect to Redis Server (probably should be extracted to config or elsewhere)
     *
     */
	protected function __initRedis(){
		try {
			$this->redis = new Redis();
			// should be changed on prod
			$this->redis->connect('127.0.0.1', 6379);
			//$this->redis->connect('192.168.99.100', 6379);
		}
		catch (Exception $e) {
			die($e->getMessage());
		}
	}

    public function highlowInit()
    {
		if ($this->Auth->user()) {
			$settings = $this->GameSetting->getHighLowSettings();
			$options['conditions'] = array('User.id' => $this->Auth->user('id'));
			$data = new \stdClass();
			$data->starting_money = floatval($this->User->find('first', $options)["User"]["balance"]);
			$data->fiches_value = [
				floatval($settings['high_low_fiches_value_1']['value']),
				floatval($settings['high_low_fiches_value_2']['value']),
				floatval($settings['high_low_fiches_value_3']['value']),
				floatval($settings['high_low_fiches_value_4']['value']),
				floatval($settings['high_low_fiches_value_5']['value'])
			];
			$data->turn_card_speed = intval($settings['high_low_card_turn_speed']['value']);
			$data->showtext_timespeed = intval($settings['high_low_show_text_speed']['value']);
			$data->show_credits = intval($settings['high_low_show_credits']['value']) == '1' ? true : false;
			$data->fullscreen = intval($settings['high_low_fullscreen']['value']) == '1' ? true : false;
			$data->check_orientation = intval($settings['high_low_check_orientation']['value']) == '1' ? true : false;
			$response = json_encode($data);
			$this->layout = 'ajax';
			$this->render(false);
			echo $response;
		} else {
			throw new Exception('Not found!', 404);
		}
    }

	public function highlowPlay()
    {
		if ($this->Auth->user()) {
			if (!$this->request->data) {
				throw new Exception('Not found!', 404);
			}
			$request = json_decode($this->request->data);
			if (!is_numeric($request->{'amount'})) {
				throw new Exception('Not found!', 404);
			}
			$userId = $this->Auth->user('id');
			$bet = round(floatval($request->{'amount'}), 2);
			$options['conditions'] = array('User.id' => $userId);
			$balance = floatval($this->User->find('first', $options)["User"]["balance"]);
			//Check if bet is lower than user balance or is 0
			if ($balance < $bet || $bet == 0) {
				throw new Exception('Not found!', 404);
			}
			//Create game outcome by calculating probability
			$outcome = (float)rand()/(float)getrandmax()*100;
			$settings = $this->GameSetting->getHighLowSettings();
			//If winnings are too big, make player lose
			if (floatval($settings['high_low_max_possible_winings']['value']) < ($balance + $bet)) {
				$outcome = floatval($settings['high_low_win_occurence']['value'])+1;
			}
			//If bet is more than auto losing bet threshold, make player lose
			if (floatval($settings['high_low_auto_lose_threshold']['value']) <= $bet) {
				$outcome = floatval($settings['high_low_win_occurence']['value'])+1;
			}
			$data = new \stdClass();
			if ($outcome < floatval($settings['high_low_win_occurence']['value'])){
				$this->User->addFunds($userId, $bet);
				$this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> won <b style='color: green'>".$bet."$</b> in <b>High Low</b>", 'High Low');
				$data->result = 'win';
			} else {
				$this->User->addFunds($userId, (-1 * abs($bet)));
				$this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> lost <b style='color: red'>".$bet."$</b> in <b>High Low</b>", 'High Low');
				$data->result = 'loss';
			}
			$response = json_encode($data);
			$this->layout = 'ajax';
			$this->render(false);
			echo $response;
		} else {
			throw new Exception('Not found!', 404);
		}
    }

	public function SoccerHeroInit(){
		if ($this->Auth->user()) {
			$this->__initRedis();
			$this->__slotRedisUnset($this->Auth->user('id'));
			$settings = $this->GameSetting->getSoccerHeroSettings();
			$options['conditions'] = array('User.id' => $this->Auth->user('id'));
			$data = new \stdClass();
			$data->starting_money = floatval($this->User->find('first', $options)["User"]["balance"]);
			$data->min_bet = floatval($settings['soccer_hero_min_bet']['value']);
			$data->max_bet = floatval($settings['soccer_hero_max_bet']['value']);
			$data->bet_time = intval($settings['soccer_hero_bet_time']['value']);
			$data->payout = floatval($settings['soccer_hero_payout']['value']);
			$data->show_credits = intval($settings['soccer_hero_show_credits']['value']) == '1' ? true : false;
			$data->fullscreen = intval($settings['soccer_hero_fullscreen']['value']) == '1' ? true : false;
			$data->check_orientation = intval($settings['soccer_hero_check_orientation']['value']) == '1' ? true : false;
			$response = json_encode($data);
			$this->layout = 'ajax';
			$this->render(false);
			echo $response;
		} else {
			throw new Exception('Not found!', 404);
		}
    }



	public function blackjackInit()
    {
		if ($this->Auth->user()) {
			$this->__initRedis();
			$this->__blackJackRedisUnset($this->Auth->user('id'));
			$settings = $this->GameSetting->getBlackJackSettings();
			$options['conditions'] = array('User.id' => $this->Auth->user('id'));
			$data = new \stdClass();
			$data->starting_money = floatval($this->User->find('first', $options)["User"]["balance"]);
			$data->min_bet = floatval($settings['black_jack_min_bet']['value']);
			$data->max_bet = floatval($settings['black_jack_max_bet']['value']);
			$data->bet_time = intval($settings['black_jack_bet_time']['value']);
			$data->payout = floatval($settings['black_jack_payout']['value']);
			$data->show_credits = intval($settings['black_jack_show_credits']['value']) == '1' ? true : false;
			$data->fullscreen = intval($settings['black_jack_fullscreen']['value']) == '1' ? true : false;
			$data->check_orientation = intval($settings['black_jack_check_orientation']['value']) == '1' ? true : false;
			$response = json_encode($data);
			$this->layout = 'ajax';
			$this->render(false);
			echo $response;
		} else {
			throw new Exception('Not found!', 404);
		}
    }

	public function blackjackDeal()
    {
		if ($this->Auth->user()) {
			if (!$this->request->data) {
				throw new Exception('Not found!', 404);
			}
			$request = json_decode($this->request->data);
			if (!is_numeric($request->{'amount'})) {
				throw new Exception('Not found!', 404);
			}
			$userId = $this->Auth->user('id');
			$this->__initRedis();
			// check if user blackJack state is set
			if ($this->redis->exists("{$userId}:blackjack-state")) {
				throw new Exception('Not found!', 404);
			}
			$bet = round(floatval($request->{'amount'}), 2);
			$options['conditions'] = array('User.id' => $userId);
			$balance = floatval($this->User->find('first', $options)["User"]["balance"]);
			// check if bet is lower than user balance or is 0
			if ($balance < $bet || $bet == 0) {
				throw new Exception('Not found!', 404);
			}
			// initialize current game flags
			$flags[GamesController::BLACK_JACK_INSURANCE] = 0;
			$flags[GamesController::BLACK_JACK_DOUBLE] = 0;
			$flags[GamesController::BLACK_JACK_SPLIT] = 0;
			$flags[GamesController::BLACK_JACK_SPLIT_DONE] = 0;
			$this->redis->set($userId.':blackjack-flags', serialize($flags));
			// set current game variables
			$this->redis->set($userId.':blackjack-state', GamesController::BLACK_JACK_STATE_DEAL_PLAYER);
			$this->redis->set($userId.':blackjack-bet', $bet);
			// write-off bet from user balance
			$this->User->addFunds($userId, (-1 * abs($bet)));

			// START GAME
			$this->__blackJackInitDeck($userId);
			$playerCard1 = $this->__blackJackDrawCard($userId);
			$playerCard2 = $this->__blackJackDrawCard($userId);
			$dealerCard1 = $this->__blackJackDrawCard($userId);

			// save drawn cards to redis
			$this->redis->set($userId.':blackjack-player-cards', serialize([$playerCard1, $playerCard2]));
			$this->redis->set($userId.':blackjack-dealer-cards', serialize([$dealerCard1]));
			// return drawn cards to frontend
			$response = json_encode([$playerCard1['card'], $dealerCard1['card'], $playerCard2['card']]);
			$this->layout = 'ajax';
			$this->render(false);
			echo $response;
		} else {
			throw new Exception('Not found!', 404);
		}
    }

	public function blackjackDealDealer()
    {
		if ($this->Auth->user()) {
			$userId = $this->Auth->user('id');
			$this->__initRedis();
			// check if user blackJack state is set to 'DEAL_PlAYER' or 'DEAL_DEALER'
			if (!($this->redis->get($userId.':blackjack-state') == GamesController::BLACK_JACK_STATE_DEAL_PLAYER) &&
			!($this->redis->get($userId.':blackjack-state') == GamesController::BLACK_JACK_STATE_DEAL_DEALER)) {
				throw new Exception('Not found!', 404);
			}
			// switch state
			$this->redis->set($userId.':blackjack-state', GamesController::BLACK_JACK_STATE_DEAL_DEALER);

			$dealerCards = unserialize($this->redis->get($userId.':blackjack-dealer-cards'));
			// GIVE DEALER A CARD
			$dealerCard = $this->__blackJackAlterOdds($userId);
			array_push($dealerCards, $dealerCard);
			$this->redis->set($userId.':blackjack-dealer-cards', serialize($dealerCards));

			$dealerValue = $this->__calculateHandValue($dealerCards);
			if ($dealerValue > 16) {
				$this->redis->set($userId.':blackjack-state', GamesController::BLACK_JACK_FINISHING);
				// check if there is a winner and act accordingly
				$this->__blackJackCheckWinner($userId);
			}

			// return drawn cards to frontend
			$response = json_encode($dealerCard['card']);
			$this->layout = 'ajax';
			$this->render(false);
			echo $response;
		} else {
			throw new Exception('Not found!', 404);
		}
    }

	protected function __blackJackAlterOdds($userId){
		$settings = $this->GameSetting->getBlackJackSettings();
		$winningPercentage = floatval($settings['black_jack_win_occurence']['value']);
		if ($winningPercentage == 100) {
			return $this->__blackJackDrawCard($userId);
		}
		$dealerCards = unserialize($this->redis->get($userId.':blackjack-dealer-cards'));
		$dealerValue = $this->__calculateHandValue($dealerCards);
		if (($dealerValue+11) < 21) {
			return $this->__blackJackDrawCard($userId);
		}
		$outcome = (float)rand()/(float)getrandmax()*100;
		if ($outcome < floatval($winningPercentage)) {
			return $this->__blackJackDrawCard($userId);
		}
		// if we are here, we need to select a card
		$cards = unserialize($this->redis->get($userId.':blackjack-player-cards'));
		$playerValue[0] = $this->__calculateHandValue($cards);

		$playerValue[1] = 0;
		if ($this->redis->exists("{$userId}:blackjack-player-cards-split")) {
			$cards = unserialize($this->redis->get($userId.':blackjack-player-cards-split'));
			$playerValue[1] = $this->__calculateHandValue($cards);
		}

		$card = null;
		$value = 0;
		while (true) {
			$card = $this->__blackJackDrawCard($userId);
			$value = $dealerValue + $card['value'];
			if (($value >= $playerValue[0] && $value >= $playerValue[1]) && $value < 22) {
				break;
			} else {
				if ($value < 17) {
					break;
				}
			}
		}
		return $card;
	}

	public function blackjackDealPlayer()
    {
		if ($this->Auth->user()) {
			$userId = $this->Auth->user('id');
			$this->__initRedis();
			// check if user blackJack state is set to 'DEAL_PLAYER'
			if (!($this->redis->get($userId.':blackjack-state') == GamesController::BLACK_JACK_STATE_DEAL_PLAYER)) {
				throw new Exception('Not found!', 404);
			}
			// switch state
			$this->redis->set($userId.':blackjack-state', GamesController::BLACK_JACK_STATE_DEAL_PLAYER);

			// check if double flag is set
			$flags = unserialize($this->redis->get($userId.':blackjack-flags'));
			if ($flags[GamesController::BLACK_JACK_DOUBLE] == 1) {
				// end player turn
				$this->redis->set($userId.':blackjack-state', GamesController::BLACK_JACK_STATE_DEAL_DEALER);
			}

			// choose a card pile depending if split is active
			$playerCards = unserialize($this->redis->get($userId.':blackjack-player-cards'));
			if ($flags[GamesController::BLACK_JACK_SPLIT] == 1 && $flags[GamesController::BLACK_JACK_SPLIT_DONE] == 0 && $this->redis->exists("{$userId}:blackjack-player-cards-split")) {
				$playerCards = unserialize($this->redis->get($userId.':blackjack-player-cards-split'));
			}

			// GIVE PLAYER A CARD
			$playerCard = $this->__blackJackDrawCard($userId);
			array_push($playerCards, $playerCard);
			$split = false;
			if ($flags[GamesController::BLACK_JACK_SPLIT] == 1 && $flags[GamesController::BLACK_JACK_SPLIT_DONE] == 0 && $this->redis->exists("{$userId}:blackjack-player-cards-split")) {
				$this->redis->set($userId.':blackjack-player-cards-split', serialize($playerCards));
				$split = true;
			} else {
				$this->redis->set($userId.':blackjack-player-cards', serialize($playerCards));
			}

			// check if user busted
			$playerValue = $this->__calculateHandValue($playerCards);
			if ($playerValue > 21) {
				$this->__blackJackPlayerLose($userId, $split);
            }

			// return drawn card to frontend
			$response = json_encode($playerCard['card']);
			$this->layout = 'ajax';
			$this->render(false);
			echo $response;
		} else {
			throw new Exception('Not found!', 404);
		}
    }

	public function blackjackInsurance()
    {
		if ($this->Auth->user()) {
			$userId = $this->Auth->user('id');
			$this->__initRedis();
			// check if user blackJack state is set to 'DEAL_PLAYER'
			if (!($this->redis->get($userId.':blackjack-state') == GamesController::BLACK_JACK_STATE_DEAL_PLAYER)) {
				throw new Exception('Not found!', 404);
			}
			// check if first dealer card is ace
			$dealerCards = unserialize($this->redis->get($userId.':blackjack-dealer-cards'));
			if (!($dealerCards[0]['value'] == 11)) {
				throw new Exception('Not found!', 404);
			}
			// check if player has exactly two cards
			$playerCards = unserialize($this->redis->get($userId.':blackjack-player-cards'));
			if (!(count($playerCards) == 2)) {
				throw new Exception('Not found!', 404);
			}
			// check if insurance is already set
			$flags = unserialize($this->redis->get($userId.':blackjack-flags'));
			if ($flags[GamesController::BLACK_JACK_INSURANCE] == 1) {
				throw new Exception('Not found!', 404);
			}
			$bet = floatval($this->redis->get($userId.':blackjack-bet'));
			$options['conditions'] = array('User.id' => $userId);
			$balance = floatval($this->User->find('first', $options)["User"]["balance"]);
			// check if balance is enough
			if ($balance < ($bet/2)) {
				throw new Exception('Not found!', 404);
			}

			// set insurance flag
			$flags[GamesController::BLACK_JACK_INSURANCE] = 1;
			$this->redis->set($userId.':blackjack-flags', serialize($flags));

			// write off additional sum from user balance
			$this->User->addFunds($userId, (-1 * abs($bet/2)));
			// increase bet by half and save it
			$bet = abs($bet) + abs($bet/2);
			$this->redis->set($userId.':blackjack-bet', $bet);

			// return success
			$response = json_encode('success');
			$this->layout = 'ajax';
			$this->render(false);
			echo $response;
		} else {
			throw new Exception('Not found!', 404);
		}
    }

	public function blackjackDouble()
    {
		if ($this->Auth->user()) {
			$userId = $this->Auth->user('id');
			$this->__initRedis();
			// check if user blackJack state is set to 'DEAL_PLAYER'
			if (!($this->redis->get($userId.':blackjack-state') == GamesController::BLACK_JACK_STATE_DEAL_PLAYER)) {
				throw new Exception('Not found!', 404);
			}
			// check if player has exactly two cards
			$playerCards = unserialize($this->redis->get($userId.':blackjack-player-cards'));
			if (!(count($playerCards) == 2)) {
				throw new Exception('Not found!', 404);
			}
			// check if double or split flag is already set
			$flags = unserialize($this->redis->get($userId.':blackjack-flags'));
			if ($flags[GamesController::BLACK_JACK_DOUBLE] == 1 || $flags[GamesController::BLACK_JACK_SPLIT] == 1) {
				throw new Exception('Not found!', 404);
			}
			$bet = floatval($this->redis->get($userId.':blackjack-bet'));
			$options['conditions'] = array('User.id' => $userId);
			$balance = floatval($this->User->find('first', $options)["User"]["balance"]);
			// check if balance is enough
			if ($balance < $bet) {
				throw new Exception('Not found!', 404);
			}

			// set double flag
			$flags[GamesController::BLACK_JACK_DOUBLE] = 1;
			$this->redis->set($userId.':blackjack-flags', serialize($flags));

			// write off additional sum from user balance
			$this->User->addFunds($userId, (-1 * abs($bet)));
			// double the bet and save it
			$bet = abs($bet) * 2;
			$this->redis->set($userId.':blackjack-bet', $bet);

			// return success
			$response = json_encode('success');
			$this->layout = 'ajax';
			$this->render(false);
			echo $response;
		} else {
			throw new Exception('Not found!', 404);
		}
    }

	public function blackjackSplit()
    {
		if ($this->Auth->user()) {
			$userId = $this->Auth->user('id');
			$this->__initRedis();
			// check if user blackJack state is set to 'DEAL_PLAYER'
			if (!($this->redis->get($userId.':blackjack-state') == GamesController::BLACK_JACK_STATE_DEAL_PLAYER)) {
				throw new Exception('Not found!', 404);
			}
			// check if player has exactly two cards
			$playerCards = unserialize($this->redis->get($userId.':blackjack-player-cards'));
			if (!(count($playerCards) == 2)) {
				throw new Exception('Not found!', 404);
			}
			// check if cards has identical value
			if (!($playerCards[0]['value'] == $playerCards[1]['value'])) {
				throw new Exception('Not found!', 404);
			}
			// check if split flag is already set
			$flags = unserialize($this->redis->get($userId.':blackjack-flags'));
			if ($flags[GamesController::BLACK_JACK_SPLIT] == 1 || $flags[GamesController::BLACK_JACK_DOUBLE] == 1) {
				throw new Exception('Not found!', 404);
			}
			$bet = floatval($this->redis->get($userId.':blackjack-bet'));
			$options['conditions'] = array('User.id' => $userId);
			$balance = floatval($this->User->find('first', $options)["User"]["balance"]);
			// check if balance is enough
			if ($balance < $bet) {
				throw new Exception('Not found!', 404);
			}

			// set split flag
			$flags[GamesController::BLACK_JACK_SPLIT] = 1;
			$this->redis->set($userId.':blackjack-flags', serialize($flags));

			// write off additional sum from user balance
			$this->User->addFunds($userId, (-1 * abs($bet)));
			// double the bet and save it
			$bet = abs($bet) * 2;
			$this->redis->set($userId.':blackjack-bet', $bet);

			// split player cards and save to redis
			$playerCards = unserialize($this->redis->get($userId.':blackjack-player-cards'));
			$playerCards[0]['value'] = ($playerCards[0]['value'] == 11) ? 1 : $playerCards[0]['value'];
			$playerCards[1]['value'] = ($playerCards[1]['value'] == 11) ? 1 : $playerCards[1]['value'];
			$this->redis->set($userId.':blackjack-player-cards', serialize([$playerCards[0]]));
			$this->redis->set($userId.':blackjack-player-cards-split', serialize([$playerCards[1]]));

			// return success
			$response = json_encode('success');
			$this->layout = 'ajax';
			$this->render(false);
			echo $response;
		} else {
			throw new Exception('Not found!', 404);
		}
    }

	public function blackjackSplitStand()
    {
		if ($this->Auth->user()) {
			$userId = $this->Auth->user('id');
			$this->__initRedis();
			// check if user blackJack state is set to 'DEAL_PLAYER'
			if (!($this->redis->get($userId.':blackjack-state') == GamesController::BLACK_JACK_STATE_DEAL_PLAYER)) {
				throw new Exception('Not found!', 404);
			}
			// check if split flag is set
			$flags = unserialize($this->redis->get($userId.':blackjack-flags'));
			if ($flags[GamesController::BLACK_JACK_SPLIT] == 0 || $flags[GamesController::BLACK_JACK_DOUBLE] == 1) {
				throw new Exception('Not found!', 404);
			}
			// set split done flag
			$flags[GamesController::BLACK_JACK_SPLIT_DONE] = 1;
			$this->redis->set($userId.':blackjack-flags', serialize($flags));

			// return success
			$response = json_encode('success');
			$this->layout = 'ajax';
			$this->render(false);
			echo $response;
		} else {
			throw new Exception('Not found!', 404);
		}
    }

	protected function __blackJackRedisUnset($userId){
		$this->redis->del($userId.':blackjack-state');
		$this->redis->del($userId.':blackjack-bet');
		$this->redis->del($userId.':blackjack-deck');
		$this->redis->del($userId.':blackjack-player-cards');
		$this->redis->del($userId.':blackjack-player-cards-split');
		$this->redis->del($userId.':blackjack-dealer-cards');
		$this->redis->del($userId.':blackjack-flags');
	}

	protected function __blackJackCheckWinner($userId){
		$flags = unserialize($this->redis->get($userId.':blackjack-flags'));
		$playerValue = [];
		if ($flags[GamesController::BLACK_JACK_SPLIT] == 1 && $this->redis->exists("{$userId}:blackjack-player-cards-split")) {
			$cards = unserialize($this->redis->get($userId.':blackjack-player-cards-split'));
			$playerValue[0]['value'] = $this->__calculateHandValue($cards);
			$playerValue[0]['bj'] = $this->__calculateBlackJack($cards);
		}
		if ($this->redis->exists("{$userId}:blackjack-player-cards")) {
			$cards = unserialize($this->redis->get($userId.':blackjack-player-cards'));
			$playerValue[1]['value'] = $this->__calculateHandValue($cards);
			$playerValue[1]['bj'] = $this->__calculateBlackJack($cards);
		}
		$dealerCards = unserialize($this->redis->get($userId.':blackjack-dealer-cards'));
		$dealerValue = $this->__calculateHandValue($dealerCards);

		if ($playerValue[1]['bj'] && ($dealerValue <> 21)) {
			$this->__blackJackPlayerWinWithBlackJack($userId);
			$this->__blackJackRedisUnset($userId);
			return;
		}

		foreach ($playerValue as $value) {
			if ($value['value'] > 21) {
                $this->__blackJackPlayerLose($userId);
            } else if ($dealerValue > 21) {
                $this->__blackJackPlayerWin($userId);
            } else if ($value['value'] > $dealerValue) {
				$this->__blackJackPlayerWin($userId);
            } else if ($value['value'] == $dealerValue && $value['value'] == 21) { //$this->__dealerHasBlackJack($userId)
                $this->__blackJackPlayerLose($userId);
            } else if ($value['value'] == $dealerValue) {
                $this->__blackJackPlayerDraw($userId);
            } else {
                $this->__blackJackPlayerLose($userId);
            }
		}

		// end current game
		$this->__blackJackRedisUnset($userId);
	}

	protected function __blackJackPlayerWinWithBlackJack($userId){
		$bet = round(floatval($this->redis->get($userId.':blackjack-bet')), 2);
		$flags = unserialize($this->redis->get($userId.':blackjack-flags'));
		$settings = $this->GameSetting->getBlackJackSettings();
		$multiplier = floatval($settings['black_jack_payout']['value']);
		$bet = $flags[GamesController::BLACK_JACK_SPLIT] == 1 ? ($bet/2) : $bet;
		$bet = $flags[GamesController::BLACK_JACK_DOUBLE] == 1 ? ($bet/2) : $bet;
		$bet = $flags[GamesController::BLACK_JACK_INSURANCE] == 1 ? ($bet/3*2) : $bet;
		$this->User->addFunds($userId, ($bet*$multiplier));
		$this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> bet <b>".$bet."$</b> and won <b style='color: green'>".($bet*$multiplier)."$</b> in <b>BlackJack</b> hitting <b>BLACKJACK</b>!", 'BlackJack');
	}

	protected function __blackJackPlayerWin($userId){
		$bet = round(floatval($this->redis->get($userId.':blackjack-bet')), 2);
		$flags = unserialize($this->redis->get($userId.':blackjack-flags'));
		if ($flags[GamesController::BLACK_JACK_SPLIT] == 1) {
			$this->User->addFunds($userId, $bet);
			$this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> bet <b>".($bet/2)."$</b> and won <b style='color: green'>".($bet)."$</b> in <b>BlackJack</b> on <b>SPLIT</b>", 'BlackJack');
		} else {
			$this->User->addFunds($userId, ($bet*2));
			if ($flags[GamesController::BLACK_JACK_DOUBLE] == 1) {
				$this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> bet <b>".($bet)."$</b> and won <b style='color: green'>".($bet*2)."$</b> in <b>BlackJack</b> on <b>DOUBLE</b>", 'BlackJack');
			} else {
				$this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> bet <b>".($bet)."$</b> and won <b style='color: green'>".($bet*2)."$</b> in <b>BlackJack</b>", 'BlackJack');
			}
		}
	}

	protected function __blackJackPlayerLose($userId, $split = false){
		$bet = round(floatval($this->redis->get($userId.':blackjack-bet')), 2);
		$flags = unserialize($this->redis->get($userId.':blackjack-flags'));
		if ($flags[GamesController::BLACK_JACK_SPLIT] == 1) {
			$this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> bet <b>".($bet/2)."$</b> and lost <b style='color: red'>".($bet/2)."$</b> in <b>BlackJack</b> on <b>SPLIT</b>", 'BlackJack');
			if ($flags[GamesController::BLACK_JACK_INSURANCE] == 1 && $this->__dealerHasBlackJack($userId)) {
				$this->User->addFunds($userId, ($bet/3));
				$this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> got <b style='color: green'>".($bet/3)."$</b> Insurance in <b>BlackJack</b> on <b>SPLIT</b>", 'BlackJack');
			}
			if ($split) {
				$this->redis->del($userId.':blackjack-player-cards-split');
			} else {
				$this->redis->del($userId.':blackjack-player-cards');
			}
		} else {
			if ($flags[GamesController::BLACK_JACK_INSURANCE] == 1) {
				if ($this->__dealerHasBlackJack($userId)) {
					$this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> bet <b>".$bet."$</b> and lost <b style='color: red'>".$bet."$</b> in <b>BlackJack</b>", 'BlackJack');
					$this->User->addFunds($userId, ($bet/3*2));
					$this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> got <b style='color: green'>".($bet/3*2)."$</b> Insurance in <b>BlackJack</b>", 'BlackJack');
				}
			} else {
				$this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> bet <b>".$bet."$</b> and lost <b style='color: red'>".$bet."$</b> in <b>BlackJack</b>", 'BlackJack');
				$this->__blackJackRedisUnset($userId);
			}
		}
	}

	protected function __blackJackPlayerDraw($userId){
		$bet = round(floatval($this->redis->get($userId.':blackjack-bet')), 2);
		$flags = unserialize($this->redis->get($userId.':blackjack-flags'));
		if ($flags[GamesController::BLACK_JACK_SPLIT] == 1) {
			$this->User->addFunds($userId, ($bet/2));
			$this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> bet <b>".($bet/2)."$</b> and won <b style='color: green'>".($bet/2)."$</b> with <b>DRAW</b> in <b>BlackJack</b> on <b>SPLIT</b>", 'BlackJack');
		} else {
			$this->User->addFunds($userId, $bet);
			if ($flags[GamesController::BLACK_JACK_DOUBLE] == 1) {
				$this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> bet <b>".$bet."$</b> and won <b style='color: green'>".$bet."$</b> with <b>DRAW</b> in <b>BlackJack</b> on <b>DOUBLE</b>", 'BlackJack');
			} else {
				$this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> bet <b>".$bet."$</b> and won <b style='color: green'>".$bet."$</b> with <b>DRAW</b> in <b>BlackJack</b>", 'BlackJack');
			}
		}
	}

	/**
     * Calculates hand value
     *
     */
	protected function __calculateHandValue($cards){
		$value = 0;
		$aces = 0;
		foreach ($cards as $card) {
			$value += $card['value'];
			if ($card['value'] == 11) {
				$aces++;
			}
		}
		for ($i=0; $i<$aces; $i++) {
			if ($value > 21) {
				$value -= 10;
			}
		}
		return $value;
	}

	/**
     * Check if dealer has blackJack
     *
     */
	protected function __dealerHasBlackJack($userId){
		$cards = unserialize($this->redis->get($userId.':blackjack-dealer-cards'));
		return $this->__calculateBlackJack($cards);
	}

	/**
     * Check if cards value is BlackJack
     *
     */
	protected function __calculateBlackJack($cards){
		if (count($cards) != 2) {
			return false;
		}
		if (($cards[0]['value'] + $cards[1]['value']) == 21) {
			return true;
		}
		return false;
	}

	/**
     * Creates blackJack deck and stores it in redis
     *
     */
	protected function __blackJackInitDeck($userId){
		$cardDeck = array();
		for($j=0; $j<52; $j++){
			$cardDeck[$j]['card'] = $j;
			$iRest=($j+1)%13;
			if($iRest>10 || $iRest === 0){
				$iRest=10;
			}
			if($iRest === 1){
				$iRest=11;
			}
			$cardDeck[$j]['value'] = $iRest;
		}
		shuffle($cardDeck);
		$this->redis->set($userId.':blackjack-deck', serialize($cardDeck));
	}

	/**
     * Draws a card from blackJack deck
     *
     */
	protected function __blackJackDrawCard($userId, $mock = false){
		$cardDeck = unserialize($this->redis->get($userId.':blackjack-deck'));
		$randomKey = array_rand($cardDeck);
		$randomCard = $cardDeck[$randomKey];
		if (!$mock) {
			array_splice($cardDeck, $randomKey, 1);
		}
		shuffle($cardDeck);
		$this->redis->set($userId.':blackjack-deck', serialize($cardDeck));

		return $randomCard;
	}

	public function rouletteInit()
    {
		if ($this->Auth->user()) {
			$settings = $this->GameSetting->getRouletteSettings();
			$options['conditions'] = array('User.id' => $this->Auth->user('id'));
			$data = new \stdClass();
			$data->starting_money = floatval($this->User->find('first', $options)["User"]["balance"]);
			$data->min_bet = floatval($settings['roulette_min_bet']['value']);
			$data->max_bet = floatval($settings['roulette_max_bet']['value']);
			$data->time_bet = intval($settings['roulette_time_bet']['value']);
			$data->time_winner = intval($settings['roulette_time_winner']['value']);
			$data->fullscreen = intval($settings['roulette_fullscreen']['value']) == '1' ? true : false;
			$data->check_orientation = intval($settings['roulette_check_orientation']['value']) == '1' ? true : false;
			$response = json_encode($data);
			$this->layout = 'ajax';
			$this->render(false);
			echo $response;
		} else {
			throw new Exception('Not found!', 404);
		}
    }

	public function roulettePlay()
    {
		if ($this->Auth->user()) {
			if (!$this->request->data) {
				throw new Exception('Not found!', 404);
			}
			$userId = $this->Auth->user('id');
			$options['conditions'] = array('User.id' => $userId);
			$balance = floatval($this->User->find('first', $options)["User"]["balance"]);

			$bets = json_decode($this->request->data);
			$winningsArray = array_fill(0, 37, 0);
			$uniqueWinningNumbers = array();
			$totalBet = 0;
			foreach ($bets as $bet) {
				$numbers = $bet->{'numbers'};
				$amount = round(floatval($bet->{'amount'}), 2);
				$totalBet += $amount;
				$ratio = 36/count($numbers);
				foreach ($numbers as $number) {
					$number = intval($number);
					if (0 <= $number && $number < 37) {
						$winningsArray[$number] += $amount * $ratio;
						if (!in_array($number, $uniqueWinningNumbers)) {
							array_push($uniqueWinningNumbers, $number);
						}
					}
				}
			}
			//Check if bet is higher than user balance or is 0
			if ($balance < $totalBet || $totalBet == 0) {
				throw new Exception('Not found!', 404);
			}
			$settings = $this->GameSetting->getRouletteSettings();
			//Check if bet is between min and max bet
			if (!($settings['roulette_min_bet']['value'] <= $totalBet && $totalBet <= $settings['roulette_max_bet']['value'])) {
				throw new Exception('Not found!', 404);
			}
			$probabilityArray = array_fill(0, 37, 0);
			$winningRatio = 1 + ((100 - intval($settings['roulette_win_occurence']['value'])) - intval($settings['roulette_win_occurence']['value']))/100;
			$winningNumberCount = count($uniqueWinningNumbers);
			$losingNumberCount = 37 - $winningNumberCount;
			for ($i=0; $i<37; $i++) {
				if ($winningsArray[$i] == 0) {
					$probabilityArray[$i] = 1/37 * $winningRatio;
				} else {
					$probabilityArray[$i] = (1 - (1/37 * $winningRatio * $losingNumberCount))/$winningNumberCount;
				}
			}
			$winner = $this->__getRouletteWinner($winningsArray, $probabilityArray);
			//If winnings are too big, make player lose
			if (floatval($settings['roulette_casino_cash']['value']) < ($balance + $winningsArray[$winner]) ||
			floatval($settings['roulette_auto_lose_threshold']['value']) <= $winningsArray[$winner]) {
				while ($winningsArray[$winner] != 0) {
					$winner = $this->__getRouletteWinner($winningsArray, $probabilityArray);
				}
			}
			$data = new \stdClass();
			if ($winningsArray[$winner] > 0){
				$this->User->addFunds($userId, (-1 * abs($totalBet)));
				$this->User->addFunds($userId, $winningsArray[$winner]);
				$this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> bet <b>".$totalBet."$</b> and won <b style='color: green'>".$winningsArray[$winner]."$</b> in <b>Roulette</b>", 'Roulette');
				$data->result = $winner;
			} else {
				$this->User->addFunds($userId, (-1 * abs($totalBet)));
				$this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> bet <b>".$totalBet."$</b> and lost <b style='color: red'>".$totalBet."$</b> in <b>Roulette</b>", 'Roulette');
				$data->result = $winner;
			}
			$response = json_encode($data);
			$this->layout = 'ajax';
			$this->render(false);
			echo $response;
		} else {
			throw new Exception('Not found!', 404);
		}
    }

	protected function __getRouletteWinner($values, $weights){
		$count = count($values);
		$i = 0;
		$n = 0;
		$num = (float)rand()/(float)getrandmax();
		while($i < $count){
			$n += $weights[$i];
			if($n >= $num){
				break;
			}
			$i++;
		}
		return $i;
	}

	public function baccaratInit()
    {
		if ($this->Auth->user()) {
			$settings = $this->GameSetting->getBaccaratSettings();
			$options['conditions'] = array('User.id' => $this->Auth->user('id'));
			$data = new \stdClass();
			$data->starting_money = floatval($this->User->find('first', $options)["User"]["balance"]);
			$data->min_bet = floatval($settings['baccarat_min_bet']['value']);
			$data->max_bet = floatval($settings['baccarat_max_bet']['value']);
			$data->multiplier_tie = floatval($settings['baccarat_multiplier_tie']['value']);
			$data->multiplier_banker = floatval($settings['baccarat_multiplier_banker']['value']);
			$data->multiplier_player = floatval($settings['baccarat_multiplier_player']['value']);
			$data->time_hand = intval($settings['baccarat_time_show_hand']['value']);
			$data->fullscreen = intval($settings['baccarat_fullscreen']['value']) == '1' ? true : false;
			$data->check_orientation = intval($settings['baccarat_check_orientation']['value']) == '1' ? true : false;
			$response = json_encode($data);
			$this->layout = 'ajax';
			$this->render(false);
			echo $response;
		} else {
			throw new Exception('Not found!', 404);
		}
    }

	public function baccaratPlay()
    {
		if ($this->Auth->user()) {
			if (!$this->request->data) {
				throw new Exception('Not found!', 404);
			}
			$userId = $this->Auth->user('id');
			$options['conditions'] = array('User.id' => $userId);
			$balance = floatval($this->User->find('first', $options)["User"]["balance"]);

			$bets = json_decode($this->request->data);
			//Check if we got exactly 3 bets
			if (count($bets) !== 3) {
				throw new Exception('Not found!', 404);
			}
			$totalBet = array_sum($bets);
			//Check if bet is higher than user balance or is 0
			if ($balance < $totalBet || $totalBet == 0) {
				throw new Exception('Not found!', 404);
			}
			$settings = $this->GameSetting->getBaccaratSettings();
			//Check if bet is between min and max bet
			if (!($settings['baccarat_min_bet']['value'] <= $totalBet && $totalBet <= $settings['baccarat_max_bet']['value'])) {
				throw new Exception('Not found!', 404);
			}
			//Create game outcome by calculating probability
			$outcome = (float)rand()/(float)getrandmax()*100;
			$winOccurence = floatval($settings['baccarat_win_occurence']['value']);
			//If bet is too big, make player lose
			if (floatval($settings['baccarat_auto_lose_threshold']['value']) <= $totalBet) {
				$outcome = $winOccurence;
			}

			$iNumBets = 0;
            for ($k=0; $k<count($bets); $k++){
                if ($bets[$k] > 0){
                    $iNumBets++;
                }
            }
			$result = null;
			if ($outcome < $winOccurence){
				//Player wins
				$betOccurences = [$settings['baccarat_bet_occurence_tie']['value'], $settings['baccarat_bet_occurence_banker']['value'], $settings['baccarat_bet_occurence_player']['value']];
                $iWinningBet = null;
                if ($iNumBets === 1) {
                    for ($k=0; $k<count($bets); $k++){
                        $iIndex = 0;
                        if ($bets[$k] !== 0){
                            $iIndex = $k;
                            break;
                        }
                    }
                    $iWinningBet = $iIndex;
					if ($iWinningBet == 0) {
						$iRandBet = (float)rand()/(float)getrandmax()*100;
						if ($iRandBet <= $betOccurences[0]) {
							$iWinningBet = 0;
						} else if ($betOccurences[0] < $iRandBet && $iRandBet <= $betOccurences[1]) {
							$iWinningBet = 1;
						} else {
							$iWinningBet = 2;
						}
					}
					if ($bets[$iWinningBet] === 0) {
						$this->__baccaratPlayerLose($userId, $result, $bets, $settings);
					}
                }else{
					do {
						$iRandBet = (float)rand()/(float)getrandmax()*100;
						if ($iRandBet <= $betOccurences[0]) {
							$iWinningBet = 0;
						} else if ($betOccurences[0] < $iRandBet && $iRandBet <= $betOccurences[1]) {
							$iWinningBet = 1;
						} else {
							$iWinningBet = 2;
						}
					} while ($bets[$iWinningBet] === 0);
                }
				$result = $iWinningBet;
				if ($bets[$iWinningBet] !== 0) {
					$this->__baccaratPlayerWin($userId, $result, $bets, $settings);
				}
			} else {
				//Player loses
                $iLosingBet = null;
                if ($iNumBets === 3){
                    //Choose worst win
					$iLosingBet = $bets[1] > $bets[2] ? 2 : 1;
                }else{
                    $aMissingBets = array();
                    for ($t=0; $t<count($bets); $t++){
                        if ($bets[$t] === 0) {
							array_push($aMissingBets, $t);
                        }
                    }
					$randomIndex = floor((float)rand()/(float)getrandmax()*count($aMissingBets));
					$iLosingBet = $aMissingBets[$randomIndex];
                }
				$result = $iLosingBet;
				$this->__baccaratPlayerLose($userId, $result, $bets, $settings);
			}

			$data = new \stdClass();
			$data->result = $result;
			$response = json_encode($data);
			$this->layout = 'ajax';
			$this->render(false);
			echo $response;
		} else {
			throw new Exception('Not found!', 404);
		}
    }

	protected function __baccaratPlayerWin($userId, $result, $bets, $settings){
		$totalBet = array_sum($bets);
		//Write off bet from player balance
		$this->User->addFunds($userId, (-1 * abs($totalBet)));
		switch ($result) {
			case 0:
				$winnings = round($bets[$result] * $settings['baccarat_multiplier_tie']['value'], 2);
				$this->User->addFunds($userId, $winnings);
				$this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> won <b style='color: green'>".$winnings."$</b> in <b>Baccarat</b> on <b>TIE</b>", 'Baccarat');
				break;
			case 1:
				$winnings = round($bets[$result] * $settings['baccarat_multiplier_banker']['value'], 2);
				$this->User->addFunds($userId, $winnings);
				$this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> won <b style='color: green'>".$winnings."$</b> in <b>Baccarat</b> on <b>BANKER</b> win", 'Baccarat');
				break;
			case 2:
				$winnings = round($bets[$result] * $settings['baccarat_multiplier_player']['value'], 2);
				$this->User->addFunds($userId, $winnings);
				$this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> won <b style='color: green'>".$winnings."$</b> in <b>Baccarat</b> on <b>PLAYER</b> win", 'Baccarat');
				break;
		}
	}

	protected function __baccaratPlayerLose($userId, $result, $bets, $settings){
		$totalBet = array_sum($bets);
		//Write off bet from player balance
		$this->User->addFunds($userId, (-1 * abs($totalBet)));
		switch ($result) {
			case 0:
				$this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> lost <b style='color: red'>".$totalBet."$</b> in <b>Baccarat</b> on <b>TIE</b>", 'Baccarat');
				break;
			case 1:
				$this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> lost <b style='color: red'>".$totalBet."$</b> in <b>Baccarat</b> on <b>BANKER</b> win", 'Baccarat');
				break;
			case 2:
				$this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> lost <b style='color: red'>".$totalBet."$</b> in <b>Baccarat</b> on <b>PLAYER</b> win", 'Baccarat');
				break;
		}
	}

	public function studInit()
    {
		if ($this->Auth->user()) {
			$this->__initRedis();
			$this->__studRedisUnset($this->Auth->user('id'));
			$settings = $this->GameSetting->getStudSettings();
			$options['conditions'] = array('User.id' => $this->Auth->user('id'));
			$data = new \stdClass();
			$data->starting_money = floatval($this->User->find('first', $options)["User"]["balance"]);
			$data->min_bet = floatval($settings['stud_min_bet']['value']);
			$data->max_bet = floatval($settings['stud_max_bet']['value']);
			$data->royal_flush = intval($settings['stud_payout_royal_flush']['value']);
			$data->straight_flush = intval($settings['stud_payout_straight_flush']['value']);
			$data->four_kind = intval($settings['stud_payout_four_of_a_kind']['value']);
			$data->full_house = intval($settings['stud_payout_full_house']['value']);
			$data->flush = intval($settings['stud_payout_flush']['value']);
			$data->straight = intval($settings['stud_payout_straight']['value']);
			$data->three_kind = intval($settings['stud_payout_three_of_a_kind']['value']);
			$data->two_pair = intval($settings['stud_payout_two_pair']['value']);
			$data->one_pair = intval($settings['stud_payout_one_pair_or_less']['value']);
			$data->time_hand = intval($settings['stud_time_show_hand']['value']);
			$data->show_credits = intval($settings['stud_show_credits']['value']) == '1' ? true : false;
			$data->fullscreen = intval($settings['stud_fullscreen']['value']) == '1' ? true : false;
			$data->check_orientation = intval($settings['stud_check_orientation']['value']) == '1' ? true : false;
			$response = json_encode($data);
			$this->layout = 'ajax';
			$this->render(false);
			echo $response;
		} else {
			throw new Exception('Not found!', 404);
		}
    }

	public function studDeal()
    {
		if ($this->Auth->user()) {
			if (!$this->request->data) {
				throw new Exception('Not found!', 404);
			}
			$request = json_decode($this->request->data);
			if (!is_numeric($request->{'amount'})) {
				throw new Exception('Not found!', 404);
			}
			$userId = $this->Auth->user('id');
			$this->__initRedis();
			// check if game session is already initialized
			if ($this->redis->exists("{$userId}:stud-playing")) {
				throw new Exception('Not found!', 404);
			}
			$bet = round(floatval($request->{'amount'}), 2);
			$options['conditions'] = array('User.id' => $userId);
			$balance = floatval($this->User->find('first', $options)["User"]["balance"]);
			// check if bet is lower than user balance or is 0
			if ($balance < $bet || $bet == 0) {
				throw new Exception('Not found!', 404);
			}
			$settings = $this->GameSetting->getStudSettings();
			//Check if bet is between min and max bet
			if (!($settings['stud_min_bet']['value'] <= $bet && $bet <= $settings['stud_max_bet']['value'])) {
				throw new Exception('Not found!', 404);
			}
			// set current game variables
			$this->redis->set($userId.':stud-playing', 1);
			$this->redis->set($userId.':stud-bet', $bet);
			// write-off bet from user balance
			$this->User->addFunds($userId, (-1 * abs($bet)));

			// START GAME
			$playerCards = null;
			$dealerCards = null;
			$outcome = (float)rand()/(float)getrandmax()*101;
			if ($outcome > floatval($settings['stud_win_occurence']['value'])){
				//LOSE
				do{
					$cards = $this->__studDrawCards();
					$playerCards = $cards[0];
					$dealerCards = $cards[1];

					$combinationPlayer = $this->__studEvaluateCards($playerCards);
					$combinationDealer = $this->__studEvaluateCards($dealerCards);

					$handResult = $this->__studDetermineWinner($playerCards,$dealerCards,$combinationPlayer,$combinationDealer);
				}while($combinationDealer === GamesController::STUD_NO_HAND || $handResult === "player" || $handResult === "dealer_no_hand" || $handResult === null);
			}else{
				//WIN
				do{
					$cards = $this->__studDrawCards();
					$playerCards = $cards[0];
					$dealerCards = $cards[1];

					$combinationPlayer = $this->__studEvaluateCards($playerCards);
					$combinationDealer = $this->__studEvaluateCards($dealerCards);

					$handResult = $this->__studDetermineWinner($playerCards,$dealerCards,$combinationPlayer,$combinationDealer);
				}while($handResult === "dealer" || $handResult === null);
			}

			// save drawn cards to redis
			$this->redis->set($userId.':stud-player-cards', serialize($playerCards));
			$this->redis->set($userId.':stud-dealer-cards', serialize($dealerCards));

			// return drawn cards to frontend
			$mockCard['fotogram'] = -1;
			$mockCard['rank'] = -1;
			$mockCard['suit'] = -1;
			$response = json_encode([$playerCards, [$mockCard, $mockCard, $mockCard, $mockCard, $dealerCards[4]]]);
			$this->layout = 'ajax';
			$this->render(false);
			echo $response;
		} else {
			throw new Exception('Not found!', 404);
		}
    }

	public function studRaise()
    {
		if ($this->Auth->user()) {
			$userId = $this->Auth->user('id');
			$this->__initRedis();
			// check if user Stud state is set to 'playing'
			if (!($this->redis->get($userId.':stud-playing') == 1)) {
				throw new Exception('Not found!', 404);
			}
			$bet = floatval($this->redis->get($userId.':stud-bet'));
			$options['conditions'] = array('User.id' => $userId);
			$balance = floatval($this->User->find('first', $options)["User"]["balance"]);
			// check if balance is enough
			if ($balance < $bet*2) {
				throw new Exception('Not found!', 404);
			}
			// write off additional sum from user balance
			$this->User->addFunds($userId, (-1 * abs($bet*2)));

			// get cards
			$playerCards = unserialize($this->redis->get($userId.':stud-player-cards'));
			$dealerCards = unserialize($this->redis->get($userId.':stud-dealer-cards'));

			// determine end result
			$combinationPlayer = $this->__studEvaluateCards($playerCards);
			$combinationDealer = $this->__studEvaluateCards($dealerCards);
			$handResult = $this->__studDetermineWinner($playerCards,$dealerCards,$combinationPlayer,$combinationDealer);

			if ($combinationDealer === GamesController::STUD_NO_HAND && $handResult !== "dealer") {
				// dealer does not qualify. in this case, dealer pays 4x ante bet
				$this->User->addFunds($userId, abs($bet*4));
				$this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> won <b style='color: green'>".($bet*4)."$</b> in <b>Caribbean Stud Poker</b> because Dealer does not qualify", 'Caribbean Stud');
			} else if ($handResult === "player") {
				$multiplier = $this->__studDetermineMultiplier($combinationPlayer);
				$amount = ($bet*2) + (($bet*2) * $multiplier);
				$this->User->addFunds($userId, abs($amount));
				$this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> won <b style='color: green'>".($amount)."$</b> in <b>Caribbean Stud Poker</b>", 'Caribbean Stud');
			} else if ($handResult === "dealer" && $combinationDealer !== GamesController::STUD_NO_HAND) {
				$this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> lost <b style='color: red'>".($bet*3)."$</b> in <b>Caribbean Stud Poker</b>", 'Caribbean Stud');
			} else {
				// stand off
				$this->User->addFunds($userId, abs($bet*3));
				$this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> won <b style='color: green'>0$</b> in <b>Caribbean Stud Poker</b> on <b>DRAW</b>", 'Caribbean Stud');
			}

			// end game
			$this->__studRedisUnset($this->Auth->user('id'));

			// return cards
			$response = json_encode([$playerCards, $dealerCards]);
			$this->layout = 'ajax';
			$this->render(false);
			echo $response;
		} else {
			throw new Exception('Not found!', 404);
		}
    }

	public function studFold()
    {
		if ($this->Auth->user()) {
			$userId = $this->Auth->user('id');
			$this->__initRedis();
			// check if user Stud state is set to 'playing'
			if (!($this->redis->get($userId.':stud-playing') == 1)) {
				throw new Exception('Not found!', 404);
			}
			$bet = floatval($this->redis->get($userId.':stud-bet'));
			$options['conditions'] = array('User.id' => $userId);
			$balance = floatval($this->User->find('first', $options)["User"]["balance"]);

			// get cards
			$playerCards = unserialize($this->redis->get($userId.':stud-player-cards'));
			$dealerCards = unserialize($this->redis->get($userId.':stud-dealer-cards'));

			// log game
			$this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> lost <b style='color: red'>".$bet."$</b> in <b>Caribbean Stud Poker</b> on <b>FOLD</b>", 'Caribbean Stud');

			// end game
			$this->__studRedisUnset($this->Auth->user('id'));

			// return cards
			$response = json_encode([$playerCards, $dealerCards]);
			$this->layout = 'ajax';
			$this->render(false);
			echo $response;
		} else {
			throw new Exception('Not found!', 404);
		}
    }

	protected function __studDetermineMultiplier($combination){
		$settings = $this->GameSetting->getStudSettings();
		if($combination === GamesController::STUD_ROYAL_FLUSH){
            return $settings['stud_payout_royal_flush']['value'];
        }else if($combination === GamesController::STUD_STRAIGHT_FLUSH){
            return $settings['stud_payout_straight_flush']['value'];
        }else if($combination === GamesController::STUD_FOUR_OF_A_KIND){
            return $settings['stud_payout_four_of_a_kind']['value'];
        }else if($combination === GamesController::STUD_FULL_HOUSE){
            return $settings['stud_payout_full_house']['value'];
        }else if($combination === GamesController::STUD_FLUSH){
            return $settings['stud_payout_flush']['value'];
        }else if($combination === GamesController::STUD_STRAIGHT){
            return $settings['stud_payout_straight']['value'];
        }else if($combination === GamesController::STUD_THREE_OF_A_KIND){
            return $settings['stud_payout_three_of_a_kind']['value'];
        }else if($combination === GamesController::STUD_TWO_PAIR){
            return $settings['stud_payout_two_pair']['value'];
        }else{
			return $settings['stud_payout_one_pair_or_less']['value'];
        }
    }

	protected function __studDetermineWinner($aHandPlayer, $aHandDealer, $iHandPlayerValue, $iHandDealerValue) {
		usort($aHandPlayer, function($a, $b) {
			return $a['rank'] - $b['rank'];
		});
		usort($aHandDealer, function($a, $b) {
			return $a['rank'] - $b['rank'];
		});

		if($iHandPlayerValue === $iHandDealerValue){
            switch($iHandPlayerValue){
                case GamesController::STUD_STRAIGHT_FLUSH:{
                        if($aHandPlayer[0]['suit'] > $aHandDealer[0]['suit']){
                            return "dealer";
                        }else if($aHandPlayer[0]['suit'] < $aHandDealer[0]['suit']){
                            return "player";
                        }else{
                            return "standoff";
                        }
                }
                case GamesController::STUD_FOUR_OF_A_KIND:{
                        if($aHandPlayer[1]['rank'] > $aHandDealer[1]['rank']){
                            return "player";
                        }else if($aHandPlayer[1]['rank'] < $aHandDealer[1]['rank']){
                            return "dealer";
                        }else{
                            return "standoff";
                        }
                }
                case GamesController::STUD_FULL_HOUSE:{
                        if($aHandPlayer[4]['rank'] > $aHandDealer[4]['rank']){
                            return "player";
                        }else if($aHandPlayer[4]['rank'] < $aHandDealer[4]['rank']){
                            return "dealer";
                        }else{
                            return "standoff";
                        }
                }
                case GamesController::STUD_FLUSH:{
                        if($aHandPlayer[0]['suit'] > $aHandDealer[0]['suit']){
                            return "dealer";
                        }else if($aHandPlayer[0]['suit'] < $aHandDealer[0]['suit']){
                            return "player";
                        }else{
                            return "standoff";
                        }
                }
                case GamesController::STUD_STRAIGHT:{
                        if($aHandPlayer[4]['rank'] > $aHandDealer[4]['rank']){
                            return "player";
                        }else if($aHandPlayer[4]['rank'] < $aHandDealer[4]['rank']){
                            return "dealer";
                        }else{
                            return "standoff";
                        }
                }
                case GamesController::STUD_THREE_OF_A_KIND:{
                        if($aHandPlayer[2]['rank'] > $aHandDealer[2]['rank']){
                            return "player";
                        }else if($aHandPlayer[2]['rank'] < $aHandDealer[2]['rank']){
                            return "dealer";
                        }else{
                            return "standoff";
                        }
                }
                case GamesController::STUD_TWO_PAIR:{
                       $iValue1 = 0;
                        for($i=count($aHandPlayer)-1;$i>0;$i--){
                            if($aHandPlayer[i]['rank'] === $aHandPlayer[i-1]['rank']){
                                $iValue1 = $aHandPlayer[$i]['rank'];
                                break;
                            }
                        }

                        $iValue2 = 0;
                        for($i=count($aHandDealer)-1;$i>0;$i--){
                            if($aHandDealer[$i]['rank'] === $aHandDealer[$i-1]['rank']){
                                $iValue2 = $aHandDealer[$i]['rank'];
                                break;
                            }
                        }

                        if($iValue1 > $iValue2){
                            return "player";
                        }else if ($iValue1 < $iValue2){
                            return "dealer";
                        }else{
                            return "standoff";
                        }
                }
                case GamesController::STUD_ONE_PAIR:{
                        $iValue1 = 0;
                        for($i=0;$i<count($aHandPlayer)-1;$i++){
                            if($aHandPlayer[$i]['rank'] === $aHandPlayer[$i+1]['rank']){
                                $iValue1 = $aHandPlayer[$i]['rank'];
                                break;
                            }
                        }

                        $iValue2 = 0;
                        for($i=0;$i<count($aHandDealer)-1;$i++){
                            if($aHandDealer[$i]['rank'] === $aHandDealer[$i+1]['rank']){
                                $iValue2 = $aHandDealer[$i]['rank'];
                                break;
                            }
                        }

                        if($iValue1 > $iValue2){
                            return "player";
                        }else if ($iValue1 < $iValue2){
                            return "dealer";
                        }else{
                            return "standoff";
                        }
                }
                case GamesController::STUD_NO_HAND:{

                        break;
                }
                default:{
                        return "standoff";
                }
            }
        }else{
            if($iHandDealerValue === GamesController::STUD_NO_HAND){
                return "dealer_no_hand";
            }

            return $iHandPlayerValue>$iHandDealerValue?"dealer":"player";
        }
	}

	/**
     * Evaluates cards and returns card strength
     *
     */
	protected function __studEvaluateCards($aHand){
		$_aSortedHand = array();
        $_aOrigSortedHand = array();
        for($i=0;$i<count($aHand);$i++){
			$_aSortedHand[$i]['rank'] = $aHand[$i]['rank'];
			$_aSortedHand[$i]['suit'] = $aHand[$i]['suit'];
			$_aOrigSortedHand[$i]['rank'] = $aHand[$i]['rank'];
			$_aOrigSortedHand[$i]['suit'] = $aHand[$i]['suit'];
        }

		usort($_aSortedHand, function($a, $b) {
			return $a['rank'] - $b['rank'];
		});
		usort($_aOrigSortedHand, function($a, $b) {
			return $a['rank'] - $b['rank'];
		});

		if($this->__studCheckForRoyalFlush($_aSortedHand)){
            return GamesController::STUD_ROYAL_FLUSH;
        }else if($this->__studCheckForStraightFlush($_aSortedHand)){
            return GamesController::STUD_STRAIGHT_FLUSH;
        }else if($this->__studCheckForFourOfAKind($_aSortedHand)){
            return GamesController::STUD_FOUR_OF_A_KIND;
        }else if($this->__studCheckForFullHouse($_aSortedHand)){
            return GamesController::STUD_FULL_HOUSE;
        }else if($this->__studCheckForFlush($_aSortedHand)){
            return GamesController::STUD_FLUSH;
        }else if($this->__studCheckForStraight($_aSortedHand)){
            return GamesController::STUD_STRAIGHT;
        }else if($this->__studCheckForThreeOfAKind($_aSortedHand)){
            return GamesController::STUD_THREE_OF_A_KIND;
        }else if($this->__studCheckForTwoPair($_aSortedHand)){
            return GamesController::STUD_TWO_PAIR;
        }else if($this->__studCheckForOnePair($_aSortedHand)){
            return GamesController::STUD_ONE_PAIR;
        }else if($this->__studCheckHighCard($_aSortedHand)){
            return GamesController::STUD_HIGH_CARD;
        }else{
            return GamesController::STUD_NO_HAND;
        }
	}

	protected function __studIsRoyalStraight($_aSortedHand){
        if($_aSortedHand[0]['rank'] === GamesController::STUD_CARD_TEN
            && $_aSortedHand[1]['rank'] === GamesController::STUD_CARD_JACK
            && $_aSortedHand[2]['rank'] === GamesController::STUD_CARD_QUEEN
            && $_aSortedHand[3]['rank'] === GamesController::STUD_CARD_KING
            && $_aSortedHand[4]['rank'] === GamesController::STUD_CARD_ACE){
            return true;
        }else{
            return false;
        }
    }

	protected function __studCheckForRoyalFlush($_aSortedHand){
        if($this->__studIsRoyalStraight($_aSortedHand) && $this->__studIsFlush($_aSortedHand)){
            return true;
        }else{
            return false;
        }
    }

	protected function __studCheckForStraightFlush($_aSortedHand){
        if($this->__studIsStraight($_aSortedHand) && $this->__studIsFlush($_aSortedHand)){
            return true;
        }else {
            return false;
        }
    }

	protected function __studCheckForFourOfAKind($_aSortedHand){
        if($_aSortedHand[0]['rank'] === $_aSortedHand[3]['rank']){
            return true;
        }else if($_aSortedHand[1]['rank'] === $_aSortedHand[4]['rank']){
            return true;
        }else{
            return false;
        }
    }

	protected function __studCheckForFullHouse($_aSortedHand){
        if(($_aSortedHand[0]['rank'] === $_aSortedHand[1]['rank'] && $_aSortedHand[2]['rank'] === $_aSortedHand[4]['rank']) || ($_aSortedHand[0]['rank'] === $_aSortedHand[2]['rank'] && $_aSortedHand[3]['rank'] === $_aSortedHand[4]['rank'])){
            return true;
        }else{
            return false;
        }
    }

	protected function __studIsFlush($_aSortedHand){
        if($_aSortedHand[0]['suit'] === $_aSortedHand[1]['suit']
            && $_aSortedHand[0]['suit'] === $_aSortedHand[2]['suit']
            && $_aSortedHand[0]['suit'] === $_aSortedHand[3]['suit']
            && $_aSortedHand[0]['suit'] === $_aSortedHand[4]['suit']){
            return true;
        }else{
            return false;
        }
    }

	protected function __studCheckForFlush($_aSortedHand){
        if($this->__studIsFlush($_aSortedHand)){
            return true;
        } else{
            return false;
        }
    }

	protected function __studIsStraight($_aSortedHand){
        $bFirstFourStraight = $_aSortedHand[0]['rank'] + 1 === $_aSortedHand[1]['rank'] && $_aSortedHand[1]['rank'] + 1 === $_aSortedHand[2]['rank'] && $_aSortedHand[2]['rank'] + 1 === $_aSortedHand[3]['rank'];

        if($bFirstFourStraight && $_aSortedHand[0]['rank'] === GamesController::STUD_CARD_TWO && $_aSortedHand[4]['rank'] === GamesController::STUD_CARD_ACE){
            return true;
        }else if($bFirstFourStraight && $_aSortedHand[3]['rank'] + 1 === $_aSortedHand[4]['rank']){
            return true;
        } else{
            return false;
        }
    }

	protected function __studCheckForStraight($_aSortedHand){
        if($this->__studIsStraight($_aSortedHand)){
            return true;
        } else{
            return false;
        }
    }

	protected function __studCheckForThreeOfAKind($_aSortedHand){
        if($_aSortedHand[0]['rank'] === $_aSortedHand[1]['rank'] && $_aSortedHand[0]['rank'] === $_aSortedHand[2]['rank']){
            return true;
        } else if($_aSortedHand[1]['rank'] === $_aSortedHand[2]['rank'] && $_aSortedHand[1]['rank'] === $_aSortedHand[3]['rank']){
            return true;
        }else if($_aSortedHand[2]['rank'] === $_aSortedHand[3]['rank'] && $_aSortedHand[2]['rank'] === $_aSortedHand[4]['rank']){
            return true;
        }else{
            return false;
        }
    }

	protected function __studCheckForTwoPair($_aSortedHand){
        if($_aSortedHand[0]['rank'] === $_aSortedHand[1]['rank'] && $_aSortedHand[2]['rank'] === $_aSortedHand[3]['rank']){
            return true;
        }else if($_aSortedHand[1]['rank'] === $_aSortedHand[2]['rank'] && $_aSortedHand[3]['rank'] === $_aSortedHand[4]['rank']){
            return true;
        }else if($_aSortedHand[0]['rank'] === $_aSortedHand[1]['rank'] && $_aSortedHand[3]['rank'] === $_aSortedHand[4]['rank']){
            return true;
        } else{
            return false;
        }
    }

	protected function __studCheckForOnePair($_aSortedHand){
		for($i = 0; $i < 4; $i++){
            if($_aSortedHand[$i]['rank'] === $_aSortedHand[$i + 1]['rank']){
                return true;
            }
        }
        return false;
	}

	protected function __studCheckHighCard($_aSortedHand){
		$bAceFound = false;
        $bKingFound = false;
        for($i = 0; $i < 5; $i++){
            if($_aSortedHand[$i]['rank'] === GamesController::STUD_CARD_ACE){
                $bAceFound = true;
            }
            if($_aSortedHand[$i]['rank'] === GamesController::STUD_CARD_KING){
                $bKingFound = true;
            }
        }
        if($bAceFound || $bKingFound){
            return true;
        }else{
            return false;
        }
	}

	/**
     * Creates and shuffles stud deck
     *
     */
	protected function __studGetShuffledDeck(){
		$iSuit = -1;
        $cardDeck = array();
        for($j=0;$j<52;$j++){
            $iRest=($j+1)%13;
            if($iRest === 1){
                $iRest=14;
                $iSuit++;
            } else if ($iRest === 0){
                $iRest = 13;
            }
			$cardDeck[$j]['fotogram'] = $j;
			$cardDeck[$j]['rank'] = $iRest;
			$cardDeck[$j]['suit'] = $iSuit;
        }
		shuffle($cardDeck);
		return $cardDeck;
	}

	/**
     * Draws cards for player and dealer
     *
     */
	protected function __studDrawCards() {
		$deck = $this->__studGetShuffledDeck();
		$playerCards = array();
		$dealerCards = array();
        for($i=0;$i<5;$i++){
			$randomKey = array_rand($deck);
			array_push($playerCards, $deck[$randomKey]);
			array_splice($deck, $randomKey, 1);
        }
		for($i=0;$i<5;$i++){
			$randomKey = array_rand($deck);
			array_push($dealerCards, $deck[$randomKey]);
			array_splice($deck, $randomKey, 1);
        }
		return array($playerCards, $dealerCards);
	}

	protected function __studRedisUnset($userId){
		$this->redis->del($userId.':stud-playing');
		$this->redis->del($userId.':stud-bet');
		$this->redis->del($userId.':stud-player-cards');
		$this->redis->del($userId.':stud-dealer-cards');
	}

	public function scratchInit()
    {
		if ($this->Auth->user()) {
			$settings = $this->GameSetting->getScratchSettings();
			$options['conditions'] = array('User.id' => $this->Auth->user('id'));
			$data = new \stdClass();
			$data->starting_money = floatval($this->User->find('first', $options)["User"]["balance"]);
			$data->bet_1 = floatval($settings['scratch_bet_1']['value']);
			$data->bet_2 = floatval($settings['scratch_bet_2']['value']);
			$data->bet_3 = floatval($settings['scratch_bet_3']['value']);
			$data->prize_1 = floatval($settings['scratch_prize_1']['value']);
			$data->prize_2 = floatval($settings['scratch_prize_2']['value']);
			$data->prize_3 = floatval($settings['scratch_prize_3']['value']);
			$data->prize_4 = floatval($settings['scratch_prize_4']['value']);
			$data->prize_5 = floatval($settings['scratch_prize_5']['value']);
			$data->prize_6 = floatval($settings['scratch_prize_6']['value']);
			$data->prize_7 = floatval($settings['scratch_prize_7']['value']);
			$data->prize_8 = floatval($settings['scratch_prize_8']['value']);
			$data->prize_9 = floatval($settings['scratch_prize_9']['value']);
			$data->fullscreen = intval($settings['scratch_fullscreen']['value']) == '1' ? true : false;
			$data->check_orientation = intval($settings['scratch_check_orientation']['value']) == '1' ? true : false;
			$data->show_credits = intval($settings['scratch_show_credits']['value']) == '1' ? true : false;
			$response = json_encode($data);
			$this->layout = 'ajax';
			$this->render(false);
			echo $response;
		} else {
			throw new Exception('Not found!', 404);
		}
    }

	public function scratchPlay()
    {
		if ($this->Auth->user()) {
			if (!$this->request->data) {
				throw new Exception('Not found!', 404);
			}
			$request = json_decode($this->request->data);
			if (!is_numeric($request->{'amount'})) {
				throw new Exception('Not found!', 404);
			}
			$userId = $this->Auth->user('id');
			$bet = round(floatval($request->{'amount'}), 2);
			$options['conditions'] = array('User.id' => $userId);
			$balance = floatval($this->User->find('first', $options)["User"]["balance"]);
			// check if bet is lower than user balance or is 0
			if ($balance < $bet || $bet == 0) {
				throw new Exception('Not found!', 404);
			}
			$settings = $this->GameSetting->getScratchSettings();
			// check if bet is correct
			if ($settings['scratch_bet_1']['value'] != $bet && $settings['scratch_bet_2']['value'] != $bet && $settings['scratch_bet_3']['value'] != $bet) {
				throw new Exception('Not found!', 404);
			}

			// write off bet from player balance
			$this->User->addFunds($userId, (-1 * abs($bet)));

			// init probability
			$prize0len=floor(100*$settings['scratch_prize_probability_1']['value']);
			$prize1len=floor(100*$settings['scratch_prize_probability_2']['value']);
			$prize2len=floor(100*$settings['scratch_prize_probability_3']['value']);
			$prize3len=floor(100*$settings['scratch_prize_probability_4']['value']);
			$prize4len=floor(100*$settings['scratch_prize_probability_5']['value']);
			$prize5len=floor(100*$settings['scratch_prize_probability_6']['value']);
			$prize6len=floor(100*$settings['scratch_prize_probability_7']['value']);
			$prize7len=floor(100*$settings['scratch_prize_probability_8']['value']);
			$prize8len=floor(100*$settings['scratch_prize_probability_9']['value']);

			$_aProbability = array();
			for($i=0; $i<$prize0len; $i++){
				array_push($_aProbability, 0);
			}
			for($i=0; $i<$prize1len; $i++){
				array_push($_aProbability, 1);
			}
			for($i=0; $i<$prize2len; $i++){
				array_push($_aProbability, 2);
			}
			for($i=0; $i<$prize3len; $i++){
				array_push($_aProbability, 3);
			}
			for($i=0; $i<$prize4len; $i++){
				array_push($_aProbability, 4);
			}
			for($i=0; $i<$prize5len; $i++){
				array_push($_aProbability, 5);
			}
			for($i=0; $i<$prize6len; $i++){
				array_push($_aProbability, 6);
			}
			for($i=0; $i<$prize7len; $i++){
				array_push($_aProbability, 7);
			}
			for($i=0; $i<$prize8len; $i++){
				array_push($_aProbability, 8);
			}

			$_aProbWin = array();
			$iCont=0;
			for($j=0; $j<$settings['scratch_win_percentage_1_rows']['value']; $j++){
				$_aProbWin[$iCont] = 1;//Number winner row
				$iCont++;
			}
			for($j=0; $j<$settings['scratch_win_percentage_2_rows']['value']; $j++){
				$_aProbWin[$iCont] = 2;//Number winner row
				$iCont++;
			}
			for($j=0; $j<$settings['scratch_win_percentage_3_rows']['value']; $j++){
				$_aProbWin[$iCont] = 3;//Number winner row
				$iCont++;
			}

			$prize = [
				$settings['scratch_prize_1']['value'],
				$settings['scratch_prize_2']['value'],
				$settings['scratch_prize_3']['value'],
				$settings['scratch_prize_4']['value'],
				$settings['scratch_prize_5']['value'],
				$settings['scratch_prize_6']['value'],
				$settings['scratch_prize_7']['value'],
				$settings['scratch_prize_8']['value'],
				$settings['scratch_prize_9']['value']
			];

			// START GAME
			$iWinProbability = (float)rand()/(float)getrandmax()*100;
			$iNumberOfWinLine = 0;
			if($iWinProbability < $settings['scratch_win_occurence']['value']){
				$iIndex = floor((float)rand()/(float)getrandmax()*count($_aProbWin));
				$iNumberOfWinLine = $_aProbWin[$iIndex];
			}

			$_aRandom = array();
			array_push($_aRandom, 0);
			array_push($_aRandom, 1);
			array_push($_aRandom, 2);
			shuffle($_aRandom);

			$iTotalWin = 0;
			$_aWin = array();
			$_aTypeWin = array();
			$_iPosWin = array();
			switch($iNumberOfWinLine){
				case 0:
				break;
				case 1:
					$_aWin[$_aRandom[0]]=true;
					$iRandomic = floor((float)rand()/(float)getrandmax()*count($_aProbability));
					$_aTypeWin[$_aRandom[0]] = $_aProbability[$iRandomic];
					$_iPosWin = $_aRandom[0];
					$iTotalWin = $prize[$_aTypeWin[$_aRandom[0]]]*$bet;
				break;
				case 2:
					$_aWin[$_aRandom[0]]=true;
					$_aWin[$_aRandom[1]]=true;
					for($i=0; $i<$iNumberOfWinLine; $i++){
						$iRandomic = floor((float)rand()/(float)getrandmax()*count($_aProbability));
						$_aTypeWin[$_aRandom[$i]] = $_aProbability[$iRandomic];
						$_iPosWin = $_aRandom[$i];
						$iTotalWin += $prize[$_aTypeWin[$_aRandom[$i]]]*$bet;
					}

				break;
				case 3:
					$_aWin[$_aRandom[0]]=true;
					$_aWin[$_aRandom[1]]=true;
					$_aWin[$_aRandom[2]]=true;

					for($i=0; $i<$iNumberOfWinLine; $i++){
						$iRandomic = floor((float)rand()/(float)getrandmax()*count($_aProbability));
						$_aTypeWin[$_aRandom[$i]] = $_aProbability[$iRandomic];
						$_iPosWin = $_aRandom[$i];
						$iTotalWin += $prize[$_aTypeWin[$_aRandom[$i]]]*$bet;
					}
				break;
			}

			// write result
			if ($iTotalWin === 0) {
				$this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> lost <b style='color: red'>".$bet."$</b> in <b>Scratch fruit</b>", 'Scratch fruit');
			} else {
				$this->User->addFunds($userId, $iTotalWin);
				$this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> won <b style='color: green'>".$iTotalWin."$</b> in <b>Scratch fruit</b>", 'Scratch fruit');
			}

			$data = new \stdClass();
			$data->result = [$_aWin, $iNumberOfWinLine, $_aTypeWin, $_aRandom, $_iPosWin, $iTotalWin];
			$response = json_encode($data);
			$this->layout = 'ajax';
			$this->render(false);
			echo $response;
		} else {
			throw new Exception('Not found!', 404);
		}
    }

	public function slotChristmasInit()
    {
		if ($this->Auth->user()) {
			$this->__initRedis();
			$this->__slotRedisUnset($this->Auth->user('id'));
			$settings = $this->GameSetting->getSlotChristmasSettings();
			$options['conditions'] = array('User.id' => $this->Auth->user('id'));
			$data = new \stdClass();
			$data->starting_money = floatval($this->User->find('first', $options)["User"]["balance"]);
			$data->min_bet = floatval($settings['slot_christmas_min_bet']['value']);
			$data->max_bet = floatval($settings['slot_christmas_max_bet']['value']);
			$data->max_hold = floatval($settings['slot_christmas_maximum_hold_reels']['value']);

			for($i=1; $i<9; $i++) {
				$paytable = $settings['slot_christmas_paytable_'.$i]['value'];
				$paytable = array_map('intval', explode(',', $paytable));
				$name = 'paytable_'.$i;
				$data->{$name} = $paytable;
			}

			for($i=1; $i<4; $i++) {
				$bonus = $settings['slot_christmas_bonus_'.$i]['value'];
				$bonus = array_map('intval', explode(',', $bonus));
				$name = 'bonus_'.$i;
				$data->{$name} = $bonus;
			}

			$data->fullscreen = intval($settings['slot_christmas_fullscreen']['value']) == '1' ? true : false;
			$data->check_orientation = intval($settings['slot_christmas_check_orientation']['value']) == '1' ? true : false;
			$data->show_credits = intval($settings['slot_christmas_show_credits']['value']) == '1' ? true : false;
			$response = json_encode($data);
			$this->layout = 'ajax';
			$this->render(false);
			echo $response;
		} else {
			throw new Exception('Not found!', 404);
		}
    }

	public function slotChickenInit()
    {
		if ($this->Auth->user()) {
			$this->__initRedis();
			$this->__slotRedisUnset($this->Auth->user('id'));
			$settings = $this->GameSetting->getSlotChickenSettings();
			$options['conditions'] = array('User.id' => $this->Auth->user('id'));
			$data = new \stdClass();
			$data->starting_money = floatval($this->User->find('first', $options)["User"]["balance"]);
			$data->min_bet = floatval($settings['slot_chicken_min_bet']['value']);
			$data->max_bet = floatval($settings['slot_chicken_max_bet']['value']);
			$data->max_hold = floatval($settings['slot_chicken_maximum_hold_reels']['value']);

			for($i=1; $i<9; $i++) {
				$paytable = $settings['slot_chicken_paytable_'.$i]['value'];
				$paytable = array_map('intval', explode(',', $paytable));
				$name = 'paytable_'.$i;
				$data->{$name} = $paytable;
			}

			for($i=1; $i<4; $i++) {
				$bonus = $settings['slot_chicken_bonus_'.$i]['value'];
				$bonus = array_map('intval', explode(',', $bonus));
				$name = 'bonus_'.$i;
				$data->{$name} = $bonus;
			}

			$data->fullscreen = intval($settings['slot_chicken_fullscreen']['value']) == '1' ? true : false;
			$data->check_orientation = intval($settings['slot_chicken_check_orientation']['value']) == '1' ? true : false;
			$data->show_credits = intval($settings['slot_chicken_show_credits']['value']) == '1' ? true : false;
			$response = json_encode($data);
			$this->layout = 'ajax';
			$this->render(false);
			echo $response;
		} else {
			throw new Exception('Not found!', 404);
		}
    }

	public function slotRamsesInit()
    {
		if ($this->Auth->user()) {
			$this->__initRedis();
			$this->__slotRedisUnset($this->Auth->user('id'));
			$settings = $this->GameSetting->getSlotRamsesSettings();
			$options['conditions'] = array('User.id' => $this->Auth->user('id'));
			$data = new \stdClass();
			$data->starting_money = floatval($this->User->find('first', $options)["User"]["balance"]);
			$data->min_bet = floatval($settings['slot_ramses_min_bet']['value']);
			$data->max_bet = floatval($settings['slot_ramses_max_bet']['value']);
			$data->max_hold = floatval($settings['slot_ramses_maximum_hold_reels']['value']);

			for($i=1; $i<9; $i++) {
				$paytable = $settings['slot_ramses_paytable_'.$i]['value'];
				$paytable = array_map('intval', explode(',', $paytable));
				$name = 'paytable_'.$i;
				$data->{$name} = $paytable;
			}

			for($i=1; $i<4; $i++) {
				$bonus = $settings['slot_ramses_bonus_'.$i]['value'];
				$bonus = array_map('intval', explode(',', $bonus));
				$name = 'bonus_'.$i;
				$data->{$name} = $bonus;
			}

			$data->fullscreen = intval($settings['slot_ramses_fullscreen']['value']) == '1' ? true : false;
			$data->check_orientation = intval($settings['slot_ramses_check_orientation']['value']) == '1' ? true : false;
			$data->show_credits = intval($settings['slot_ramses_show_credits']['value']) == '1' ? true : false;
			$response = json_encode($data);
			$this->layout = 'ajax';
			$this->render(false);
			echo $response;
		} else {
			throw new Exception('Not found!', 404);
		}
    }

	public function slotSpaceInit()
    {
		if ($this->Auth->user()) {
			$this->__initRedis();
			$this->__slotRedisUnset($this->Auth->user('id'));
			$settings = $this->GameSetting->getSlotSpaceSettings();
			$options['conditions'] = array('User.id' => $this->Auth->user('id'));
			$data = new \stdClass();
			$data->starting_money = floatval($this->User->find('first', $options)["User"]["balance"]);
			$data->min_bet = floatval($settings['slot_space_min_bet']['value']);
			$data->max_bet = floatval($settings['slot_space_max_bet']['value']);
			$data->max_hold = floatval($settings['slot_space_maximum_hold_reels']['value']);

			for($i=1; $i<9; $i++) {
				$paytable = $settings['slot_space_paytable_'.$i]['value'];
				$paytable = array_map('intval', explode(',', $paytable));
				$name = 'paytable_'.$i;
				$data->{$name} = $paytable;
			}

			for($i=1; $i<4; $i++) {
				$bonus = $settings['slot_space_bonus_'.$i]['value'];
				$bonus = array_map('intval', explode(',', $bonus));
				$name = 'bonus_'.$i;
				$data->{$name} = $bonus;
			}

			$data->fullscreen = intval($settings['slot_space_fullscreen']['value']) == '1' ? true : false;
			$data->check_orientation = intval($settings['slot_space_check_orientation']['value']) == '1' ? true : false;
			$data->show_credits = intval($settings['slot_space_show_credits']['value']) == '1' ? true : false;
			$response = json_encode($data);
			$this->layout = 'ajax';
			$this->render(false);
			echo $response;
		} else {
			throw new Exception('Not found!', 404);
		}
    }

    public function slotSoccer3dInit()
    {
        if ($this->Auth->user()) {
            $this->__initRedis();
            $this->__slotRedisUnset($this->Auth->user('id'));
            $settings = $this->GameSetting->getSlotSoccer3dSettings();
            $options['conditions'] = array('User.id' => $this->Auth->user('id'));
            $data = new \stdClass();
            $data->starting_money = floatval($this->User->find('first', $options)["User"]["balance"]);
            $data->min_bet = floatval($settings['slot_soccer3d_min_bet']['value']);
            $data->max_bet = floatval($settings['slot_soccer3d_max_bet']['value']);

            for($i=1; $i<11; $i++) {
                $paytable = $settings['slot_soccer3d_paytable_'.$i]['value'];
                $paytable = array_map('intval', explode(',', $paytable));
                $name = 'paytable_'.$i;
                $data->{$name} = $paytable;
            }

            for($i=1; $i<6; $i++) {
                $bonus = $settings['slot_soccer3d_bonus_'.$i]['value'];
                $bonus = array_map('intval', explode(',', $bonus));
                $name = 'bonus_'.$i;
                $data->{$name} = $bonus;
            }

            for($i=1; $i<4; $i++) {
                $freespins = $settings['slot_soccer3d_freespin_'.$i]['value'];
                $freespins = array_map('intval', explode(',', $freespins));
                $name = 'freespins_'.$i;
                $data->{$name} = $freespins;
            }

            $data->fullscreen = intval($settings['slot_soccer3d_fullscreen']['value']) == '1' ? true : false;
            $data->check_orientation = intval($settings['slot_soccer3d_check_orientation']['value']) == '1' ? true : false;
            $data->show_credits = intval($settings['slot_soccer3d_show_credits']['value']) == '1' ? true : false;
            $response = json_encode($data);
            $this->layout = 'ajax';
            $this->render(false);
            echo $response;
        } else {
            throw new Exception('Not found!', 404);
        }
    }

    public function slotArabianInit()
    {
        if ($this->Auth->user()) {
            $this->__initRedis();
            $this->__slotRedisUnset($this->Auth->user('id'));
            $settings = $this->GameSetting->getSlotArabianSettings();
            $options['conditions'] = array('User.id' => $this->Auth->user('id'));
            $data = new \stdClass();
            $data->starting_money = floatval($this->User->find('first', $options)["User"]["balance"]);
            $data->min_bet = floatval($settings['slot_arabian_min_bet']['value']);
            $data->max_bet = floatval($settings['slot_arabian_max_bet']['value']);

            for($i=1; $i<8; $i++) {
                $paytable = $settings['slot_arabian_paytable_'.$i]['value'];
                $paytable = array_map('intval', explode(',', $paytable));
                $name = 'paytable_'.$i;
                $data->{$name} = $paytable;
            }

            $bonus = $settings['slot_arabian_bonus']['value'];
            $data->bonus = array_map('intval', explode(',', $bonus));

            for($i=1; $i<4; $i++) {
                $freespins = $settings['slot_arabian_freespin_'.$i]['value'];
                $freespins = array_map('intval', explode(',', $freespins));
                $name = 'freespins_'.$i;
                $data->{$name} = $freespins;
            }

            $data->fullscreen = intval($settings['slot_arabian_fullscreen']['value']) == '1' ? true : false;
            $data->check_orientation = intval($settings['slot_arabian_check_orientation']['value']) == '1' ? true : false;
            $data->show_credits = intval($settings['slot_arabian_show_credits']['value']) == '1' ? true : false;
            $response = json_encode($data);
            $this->layout = 'ajax';
            $this->render(false);
            echo $response;
        } else {
            throw new Exception('Not found!', 404);
        }
    }

	public function slotFruitsInit()
    {
		if ($this->Auth->user()) {
			$this->__initRedis();
			$this->__slotRedisUnset($this->Auth->user('id'));
			$settings = $this->GameSetting->getSlotFruitsSettings();
			$options['conditions'] = array('User.id' => $this->Auth->user('id'));
			$data = new \stdClass();
			$data->starting_money = floatval($this->User->find('first', $options)["User"]["balance"]);
			$data->min_bet = floatval($settings['slot_fruits_min_bet']['value']);
			$data->max_bet = floatval($settings['slot_fruits_max_bet']['value']);

			for($i=1; $i<8; $i++) {
				$paytable = $settings['slot_fruits_paytable_'.$i]['value'];
				$paytable = array_map('intval', explode(',', $paytable));
				$name = 'paytable_'.$i;
				$data->{$name} = $paytable;
			}

			$data->fullscreen = intval($settings['slot_fruits_fullscreen']['value']) == '1' ? true : false;
			$data->check_orientation = intval($settings['slot_fruits_check_orientation']['value']) == '1' ? true : false;
			$data->show_credits = intval($settings['slot_fruits_show_credits']['value']) == '1' ? true : false;
			$response = json_encode($data);
			$this->layout = 'ajax';
			$this->render(false);
			echo $response;
		} else {
			throw new Exception('Not found!', 404);
		}
    }

    public function slotSoccerInit()
    {
        if ($this->Auth->user()) {
            $this->__initRedis();
            $this->__slotRedisUnset($this->Auth->user('id'));
            $settings = $this->GameSetting->getSlotSoccerSettings();
            $options['conditions'] = array('User.id' => $this->Auth->user('id'));
            $data = new \stdClass();
            $data->starting_money = floatval($this->User->find('first', $options)["User"]["balance"]);
            $data->min_bet = floatval($settings['slot_soccer_min_bet']['value']);
            $data->max_bet = floatval($settings['slot_soccer_max_bet']['value']);

            for($i=1; $i<8; $i++) {
                $paytable = $settings['slot_soccer_paytable_'.$i]['value'];
                $paytable = array_map('intval', explode(',', $paytable));
                $name = 'paytable_'.$i;
                $data->{$name} = $paytable;
            }

            $data->fullscreen = intval($settings['slot_soccer_fullscreen']['value']) == '1' ? true : false;
            $data->check_orientation = intval($settings['slot_soccer_check_orientation']['value']) == '1' ? true : false;
            $data->show_credits = intval($settings['slot_soccer_show_credits']['value']) == '1' ? true : false;
            $response = json_encode($data);
            $this->layout = 'ajax';
            $this->render(false);
            echo $response;
        } else {
            throw new Exception('Not found!', 404);
        }
    }

	public function slotChristmasPlay()
    {
		if ($this->Auth->user()) {
			if (!$this->request->data) {
				throw new Exception('Not found!', 404);
			}
			$request = json_decode($this->request->data);
			if (!is_numeric($request->{'amount'})) {
				throw new Exception('Not found!', 404);
			}
			if ($request->{'lines'} < 1 && $request->{'lines'} > 5) {
				throw new Exception('Not found!', 404);
			}
			$this->__initRedis();
			$userId = $this->Auth->user('id');
			$lines = floatval($request->{'lines'});
			$bet = round(floatval($request->{'amount'}), 2);
			$bet *= $lines;
			$hold = $request->{'hold'};
			$options['conditions'] = array('User.id' => $userId);
			$balance = floatval($this->User->find('first', $options)["User"]["balance"]);
			// check if bet is lower than user balance or is 0
			if ($balance < $bet || $bet == 0) {
				throw new Exception('Not found!', 404);
			}
			$settings = $this->GameSetting->getSlotChristmasSettings();
			// check if hold reels is correct
			if (count(array_filter($hold)) > $settings['slot_christmas_maximum_hold_reels']['value']) {
				throw new Exception('Not found!', 404);
			}

			if (!$this->redis->exists("{$userId}:slots-hold-available")) {
				$hold = [false, false, false, false, false];
				// write off bet from player balance
				$this->User->addFunds($userId, (-1 * abs($bet)));
			}

			// START GAME

			$s_aPaylineCombo[0] = [['row'=>1,'col'=>0],['row'=>1,'col'=>1],['row'=>1,'col'=>2],['row'=>1,'col'=>3],['row'=>1,'col'=>4]];
			$s_aPaylineCombo[1] = [['row'=>0,'col'=>0],['row'=>0,'col'=>1],['row'=>0,'col'=>2],['row'=>0,'col'=>3],['row'=>0,'col'=>4]];
			$s_aPaylineCombo[2] = [['row'=>2,'col'=>0],['row'=>2,'col'=>1],['row'=>2,'col'=>2],['row'=>2,'col'=>3],['row'=>2,'col'=>4]];
			$s_aPaylineCombo[3] = [['row'=>0,'col'=>0],['row'=>1,'col'=>1],['row'=>2,'col'=>2],['row'=>1,'col'=>3],['row'=>0,'col'=>4]];
			$s_aPaylineCombo[4] = [['row'=>2,'col'=>0],['row'=>1,'col'=>1],['row'=>0,'col'=>2],['row'=>1,'col'=>3],['row'=>2,'col'=>4]];

			$symbols = $this->__slotInitSymbols();

			$outcome = $this->__slotPlay($userId, $lines, $s_aPaylineCombo, $symbols, $settings, 'christmas', $bet, $hold, true, 5);

			// BONUS GAME
			$bonus = 0;
			$bonusIndex = 0;
			if ($outcome[1] === true) {
				$bonusArray = $this->__slotBonus($outcome[5], $settings, 'christmas');
				$bonus = $bonusArray[0];
				$bonusIndex = $bonusArray[1];
			}

			$this->__slotCalculateWin($userId, $outcome, $bonus, 'Christmas', $lines, $bet);

			// return variables to frontend
			$response = json_encode([$outcome, $bonusIndex]);
			$this->layout = 'ajax';
			$this->render(false);
			echo $response;
		} else {
			throw new Exception('Not found!', 404);
		}
    }

	public function slotChickenPlay()
    {
		if ($this->Auth->user()) {
			if (!$this->request->data) {
				throw new Exception('Not found!', 404);
			}
			$request = json_decode($this->request->data);
			if (!is_numeric($request->{'amount'})) {
				throw new Exception('Not found!', 404);
			}
			if ($request->{'lines'} < 1 && $request->{'lines'} > 5) {
				throw new Exception('Not found!', 404);
			}
			$this->__initRedis();
			$userId = $this->Auth->user('id');
			$lines = floatval($request->{'lines'});
			$bet = round(floatval($request->{'amount'}), 2);
			$bet *= $lines;
			$hold = $request->{'hold'};
			$options['conditions'] = array('User.id' => $userId);
			$balance = floatval($this->User->find('first', $options)["User"]["balance"]);
			// check if bet is lower than user balance or is 0
			if ($balance < $bet || $bet == 0) {
				throw new Exception('Not found!', 404);
			}
			$settings = $this->GameSetting->getSlotChickenSettings();
			// check if hold reels is correct
			if (count(array_filter($hold)) > $settings['slot_chicken_maximum_hold_reels']['value']) {
				throw new Exception('Not found!', 404);
			}

			if (!$this->redis->exists("{$userId}:slots-hold-available")) {
				$hold = [false, false, false, false, false];
				// write off bet from player balance
				$this->User->addFunds($userId, (-1 * abs($bet)));
			}

			// START GAME

			$s_aPaylineCombo[0] = [['row'=>1,'col'=>0],['row'=>1,'col'=>1],['row'=>1,'col'=>2],['row'=>1,'col'=>3],['row'=>1,'col'=>4]];
			$s_aPaylineCombo[1] = [['row'=>0,'col'=>0],['row'=>0,'col'=>1],['row'=>0,'col'=>2],['row'=>0,'col'=>3],['row'=>0,'col'=>4]];
			$s_aPaylineCombo[2] = [['row'=>2,'col'=>0],['row'=>2,'col'=>1],['row'=>2,'col'=>2],['row'=>2,'col'=>3],['row'=>2,'col'=>4]];
			$s_aPaylineCombo[3] = [['row'=>0,'col'=>0],['row'=>1,'col'=>1],['row'=>2,'col'=>2],['row'=>1,'col'=>3],['row'=>0,'col'=>4]];
			$s_aPaylineCombo[4] = [['row'=>2,'col'=>0],['row'=>1,'col'=>1],['row'=>0,'col'=>2],['row'=>1,'col'=>3],['row'=>2,'col'=>4]];

			$symbols = $this->__slotInitSymbols();

			$outcome = $this->__slotPlay($userId, $lines, $s_aPaylineCombo, $symbols, $settings, 'chicken', $bet, $hold, true, 5);

			// BONUS GAME
			$bonus = 0;
			$bonusIndex = 0;
			if ($outcome[1] === true) {
				$bonusArray = $this->__slotBonus($outcome[5], $settings, 'chicken');
				$bonus = $bonusArray[0];
				$bonusIndex = $bonusArray[1];
			}

			$this->__slotCalculateWin($userId, $outcome, $bonus, 'chicken', $lines, $bet);

			// return variables to frontend
			$response = json_encode([$outcome, $bonusIndex]);
			$this->layout = 'ajax';
			$this->render(false);
			echo $response;
		} else {
			throw new Exception('Not found!', 404);
		}
    }

	public function slotRamsesPlay()
    {
		if ($this->Auth->user()) {
			if (!$this->request->data) {
				throw new Exception('Not found!', 404);
			}
			$request = json_decode($this->request->data);
			if (!is_numeric($request->{'amount'})) {
				throw new Exception('Not found!', 404);
			}
			if ($request->{'lines'} < 1 && $request->{'lines'} > 5) {
				throw new Exception('Not found!', 404);
			}
			$this->__initRedis();
			$userId = $this->Auth->user('id');
			$lines = floatval($request->{'lines'});
			$bet = round(floatval($request->{'amount'}), 2);
			$bet *= $lines;
			$hold = $request->{'hold'};
			$options['conditions'] = array('User.id' => $userId);
			$balance = floatval($this->User->find('first', $options)["User"]["balance"]);
			// check if bet is lower than user balance or is 0
			if ($balance < $bet || $bet == 0) {
				throw new Exception('Not found!', 404);
			}
			$settings = $this->GameSetting->getSlotRamsesSettings();
			// check if hold reels is correct
			if (count(array_filter($hold)) > $settings['slot_ramses_maximum_hold_reels']['value']) {
				throw new Exception('Not found!', 404);
			}

			if (!$this->redis->exists("{$userId}:slots-hold-available")) {
				$hold = [false, false, false, false, false];
				// write off bet from player balance
				$this->User->addFunds($userId, (-1 * abs($bet)));
			}

			// START GAME

			$s_aPaylineCombo[0] = [['row'=>1,'col'=>0],['row'=>1,'col'=>1],['row'=>1,'col'=>2],['row'=>1,'col'=>3],['row'=>1,'col'=>4]];
			$s_aPaylineCombo[1] = [['row'=>0,'col'=>0],['row'=>0,'col'=>1],['row'=>0,'col'=>2],['row'=>0,'col'=>3],['row'=>0,'col'=>4]];
			$s_aPaylineCombo[2] = [['row'=>2,'col'=>0],['row'=>2,'col'=>1],['row'=>2,'col'=>2],['row'=>2,'col'=>3],['row'=>2,'col'=>4]];
			$s_aPaylineCombo[3] = [['row'=>0,'col'=>0],['row'=>1,'col'=>1],['row'=>2,'col'=>2],['row'=>1,'col'=>3],['row'=>0,'col'=>4]];
			$s_aPaylineCombo[4] = [['row'=>2,'col'=>0],['row'=>1,'col'=>1],['row'=>0,'col'=>2],['row'=>1,'col'=>3],['row'=>2,'col'=>4]];

			$symbols = $this->__slotInitSymbols();

			$outcome = $this->__slotPlay($userId, $lines, $s_aPaylineCombo, $symbols, $settings, 'ramses', $bet, $hold, true, 5);

			// BONUS GAME
			$bonus = 0;
			$bonusIndex = 0;
			if ($outcome[1] === true) {
				$bonusArray = $this->__slotBonus($outcome[5], $settings, 'ramses');
				$bonus = $bonusArray[0];
				$bonusIndex = $bonusArray[1];
			}

			$this->__slotCalculateWin($userId, $outcome, $bonus, 'ramses', $lines, $bet);

			// return variables to frontend
			$response = json_encode([$outcome, $bonusIndex]);
			$this->layout = 'ajax';
			$this->render(false);
			echo $response;
		} else {
			throw new Exception('Not found!', 404);
		}
    }

	public function slotSpacePlay()
    {
		if ($this->Auth->user()) {
			if (!$this->request->data) {
				throw new Exception('Not found!', 404);
			}
			$request = json_decode($this->request->data);
			if (!is_numeric($request->{'amount'})) {
				throw new Exception('Not found!', 404);
			}
			if ($request->{'lines'} < 1 && $request->{'lines'} > 5) {
				throw new Exception('Not found!', 404);
			}
			$this->__initRedis();
			$userId = $this->Auth->user('id');
			$lines = floatval($request->{'lines'});
			$bet = round(floatval($request->{'amount'}), 2);
			$bet *= $lines;
			$hold = $request->{'hold'};
			$options['conditions'] = array('User.id' => $userId);
			$balance = floatval($this->User->find('first', $options)["User"]["balance"]);
			// check if bet is lower than user balance or is 0
			if ($balance < $bet || $bet == 0) {
				throw new Exception('Not found!', 404);
			}
			$settings = $this->GameSetting->getSlotSpaceSettings();
			// check if hold reels is correct
			if (count(array_filter($hold)) > $settings['slot_space_maximum_hold_reels']['value']) {
				throw new Exception('Not found!', 404);
			}

			if (!$this->redis->exists("{$userId}:slots-hold-available")) {
				$hold = [false, false, false, false, false];
				// write off bet from player balance
				$this->User->addFunds($userId, (-1 * abs($bet)));
			}

			// START GAME

			$s_aPaylineCombo[0] = [['row'=>1,'col'=>0],['row'=>1,'col'=>1],['row'=>1,'col'=>2],['row'=>1,'col'=>3],['row'=>1,'col'=>4]];
			$s_aPaylineCombo[1] = [['row'=>0,'col'=>0],['row'=>0,'col'=>1],['row'=>0,'col'=>2],['row'=>0,'col'=>3],['row'=>0,'col'=>4]];
			$s_aPaylineCombo[2] = [['row'=>2,'col'=>0],['row'=>2,'col'=>1],['row'=>2,'col'=>2],['row'=>2,'col'=>3],['row'=>2,'col'=>4]];
			$s_aPaylineCombo[3] = [['row'=>0,'col'=>0],['row'=>1,'col'=>1],['row'=>2,'col'=>2],['row'=>1,'col'=>3],['row'=>0,'col'=>4]];
			$s_aPaylineCombo[4] = [['row'=>2,'col'=>0],['row'=>1,'col'=>1],['row'=>0,'col'=>2],['row'=>1,'col'=>3],['row'=>2,'col'=>4]];

			$symbols = $this->__slotInitSymbols();

			$outcome = $this->__slotPlay($userId, $lines, $s_aPaylineCombo, $symbols, $settings, 'space', $bet, $hold, true, 5);

			// BONUS GAME
			$bonus = 0;
			$bonusIndex = 0;
			if ($outcome[1] === true) {
				$bonusArray = $this->__slotBonus($outcome[5], $settings, 'space');
				$bonus = $bonusArray[0];
				$bonusIndex = $bonusArray[1];
			}

			$this->__slotCalculateWin($userId, $outcome, $bonus, 'space', $lines, $bet);

			// return variables to frontend
			$response = json_encode([$outcome, $bonusIndex]);
			$this->layout = 'ajax';
			$this->render(false);
			echo $response;
		} else {
			throw new Exception('Not found!', 404);
		}
    }

	public function slotFruitsPlay()
    {
		if ($this->Auth->user()) {
			if (!$this->request->data) {
				throw new Exception('Not found!', 404);
			}
			$request = json_decode($this->request->data);
			if (!is_numeric($request->{'amount'})) {
				throw new Exception('Not found!', 404);
			}
			if ($request->{'lines'} < 1 && $request->{'lines'} > 20) {
				throw new Exception('Not found!', 404);
			}
			$this->__initRedis();
			$userId = $this->Auth->user('id');
			$lines = floatval($request->{'lines'});
			$bet = round(floatval($request->{'amount'}), 2);
			$bet *= $lines;
			$options['conditions'] = array('User.id' => $userId);
			$balance = floatval($this->User->find('first', $options)["User"]["balance"]);
			// check if bet is lower than user balance or is 0
			if ($balance < $bet || $bet == 0) {
				throw new Exception('Not found!', 404);
			}
			$settings = $this->GameSetting->getSlotFruitsSettings();

			$hold = [false, false, false, false, false];
			// write off bet from player balance
			$this->User->addFunds($userId, (-1 * abs($bet)));

			// START GAME

			$s_aPaylineCombo[0] = [['row'=>1,'col'=>0],['row'=>1,'col'=>1],['row'=>1,'col'=>2],['row'=>1,'col'=>3],['row'=>1,'col'=>4]];
			$s_aPaylineCombo[1] = [['row'=>0,'col'=>0],['row'=>0,'col'=>1],['row'=>0,'col'=>2],['row'=>0,'col'=>3],['row'=>0,'col'=>4]];
			$s_aPaylineCombo[2] = [['row'=>2,'col'=>0],['row'=>2,'col'=>1],['row'=>2,'col'=>2],['row'=>2,'col'=>3],['row'=>2,'col'=>4]];
			$s_aPaylineCombo[3] = [['row'=>0,'col'=>0],['row'=>1,'col'=>1],['row'=>2,'col'=>2],['row'=>1,'col'=>3],['row'=>0,'col'=>4]];
			$s_aPaylineCombo[4] = [['row'=>2,'col'=>0],['row'=>1,'col'=>1],['row'=>0,'col'=>2],['row'=>1,'col'=>3],['row'=>2,'col'=>4]];
			$s_aPaylineCombo[5] = [['row'=>1,'col'=>0],['row'=>0,'col'=>1],['row'=>0,'col'=>2],['row'=>0,'col'=>3],['row'=>1,'col'=>4]];
			$s_aPaylineCombo[6] = [['row'=>1,'col'=>0],['row'=>2,'col'=>1],['row'=>2,'col'=>2],['row'=>2,'col'=>3],['row'=>1,'col'=>4]];
			$s_aPaylineCombo[7] = [['row'=>0,'col'=>0],['row'=>0,'col'=>1],['row'=>1,'col'=>2],['row'=>2,'col'=>3],['row'=>2,'col'=>4]];
			$s_aPaylineCombo[8] = [['row'=>2,'col'=>0],['row'=>2,'col'=>1],['row'=>1,'col'=>2],['row'=>0,'col'=>3],['row'=>0,'col'=>4]];
			$s_aPaylineCombo[9] = [['row'=>1,'col'=>0],['row'=>2,'col'=>1],['row'=>1,'col'=>2],['row'=>0,'col'=>3],['row'=>1,'col'=>4]];
			$s_aPaylineCombo[10] = [['row'=>2,'col'=>0],['row'=>0,'col'=>1],['row'=>1,'col'=>2],['row'=>2,'col'=>3],['row'=>1,'col'=>4]];
			$s_aPaylineCombo[11] = [['row'=>0,'col'=>0],['row'=>1,'col'=>1],['row'=>1,'col'=>2],['row'=>1,'col'=>3],['row'=>0,'col'=>4]];
			$s_aPaylineCombo[12] = [['row'=>2,'col'=>0],['row'=>1,'col'=>1],['row'=>1,'col'=>2],['row'=>1,'col'=>3],['row'=>2,'col'=>4]];
			$s_aPaylineCombo[13] = [['row'=>0,'col'=>0],['row'=>1,'col'=>1],['row'=>0,'col'=>2],['row'=>1,'col'=>3],['row'=>0,'col'=>4]];
			$s_aPaylineCombo[14] = [['row'=>2,'col'=>0],['row'=>1,'col'=>1],['row'=>2,'col'=>2],['row'=>1,'col'=>3],['row'=>2,'col'=>4]];
			$s_aPaylineCombo[15] = [['row'=>1,'col'=>0],['row'=>1,'col'=>1],['row'=>0,'col'=>2],['row'=>1,'col'=>3],['row'=>1,'col'=>4]];
			$s_aPaylineCombo[16] = [['row'=>1,'col'=>0],['row'=>1,'col'=>1],['row'=>2,'col'=>2],['row'=>1,'col'=>3],['row'=>1,'col'=>4]];
			$s_aPaylineCombo[17] = [['row'=>0,'col'=>0],['row'=>0,'col'=>1],['row'=>2,'col'=>2],['row'=>0,'col'=>3],['row'=>0,'col'=>4]];
			$s_aPaylineCombo[18] = [['row'=>2,'col'=>0],['row'=>2,'col'=>1],['row'=>0,'col'=>2],['row'=>2,'col'=>3],['row'=>2,'col'=>4]];
			$s_aPaylineCombo[19] = [['row'=>0,'col'=>0],['row'=>2,'col'=>1],['row'=>2,'col'=>2],['row'=>2,'col'=>3],['row'=>0,'col'=>4]];

			$symbols = array();

			//OCCURENCE FOR SYMBOL 1
			for($i=0;$i<1;$i++){
				array_push($symbols, 1);
			}

			//OCCURENCE FOR SYMBOL 2
			for($i=0;$i<2;$i++){
				array_push($symbols, 2);
			}

			//OCCURENCE FOR SYMBOL 3
			for($i=0;$i<3;$i++){
				array_push($symbols, 3);
			}

			//OCCURENCE FOR SYMBOL 4
			for($i=0;$i<4;$i++){
				array_push($symbols, 4);
			}

			//OCCURENCE FOR SYMBOL 5
			for($i=0;$i<4;$i++){
				array_push($symbols, 5);
			}

			//OCCURENCE FOR SYMBOL 6
			for($i=0;$i<6;$i++){
				array_push($symbols, 6);
			}

			//OCCURENCE FOR SYMBOL 7
			for($i=0;$i<6;$i++){
				array_push($symbols, 7);
			}

			//OCCURENCE FOR SYMBOL WILD
			for($i=0;$i<1;$i++){
				array_push($symbols, GamesController::SLOT_WILD_SYMBOL);
			}

			$outcome = $this->__slotPlay($userId, $lines, $s_aPaylineCombo, $symbols, $settings, 'fruits', $bet, $hold, false, 20);

			$this->__slotCalculateWin($userId, $outcome, 0, 'fruits', $lines, $bet);

			// return variables to frontend
			$response = json_encode([$outcome]);
			$this->layout = 'ajax';
			$this->render(false);
			echo $response;
		} else {
			throw new Exception('Not found!', 404);
		}
    }

    public function slotSoccerPlay()
    {
        if ($this->Auth->user()) {
            if (!$this->request->data) {
                throw new Exception('Not found!', 404);
            }
            $request = json_decode($this->request->data);
            if (!is_numeric($request->{'amount'})) {
                throw new Exception('Not found!', 404);
            }
            if ($request->{'lines'} < 1 && $request->{'lines'} > 20) {
                throw new Exception('Not found!', 404);
            }
            $this->__initRedis();
            $userId = $this->Auth->user('id');
            $lines = floatval($request->{'lines'});
            $bet = round(floatval($request->{'amount'}), 2);
            $bet *= $lines;
            $options['conditions'] = array('User.id' => $userId);
            $balance = floatval($this->User->find('first', $options)["User"]["balance"]);
            // check if bet is lower than user balance or is 0
            if ($balance < $bet || $bet == 0) {
                throw new Exception('Not found!', 404);
            }
            $settings = $this->GameSetting->getSlotSoccerSettings();

            $hold = [false, false, false, false, false];
            // write off bet from player balance
            $this->User->addFunds($userId, (-1 * abs($bet)));

            // START GAME

            $s_aPaylineCombo[0] = [['row'=>1,'col'=>0],['row'=>1,'col'=>1],['row'=>1,'col'=>2],['row'=>1,'col'=>3],['row'=>1,'col'=>4]];
            $s_aPaylineCombo[1] = [['row'=>0,'col'=>0],['row'=>0,'col'=>1],['row'=>0,'col'=>2],['row'=>0,'col'=>3],['row'=>0,'col'=>4]];
            $s_aPaylineCombo[2] = [['row'=>2,'col'=>0],['row'=>2,'col'=>1],['row'=>2,'col'=>2],['row'=>2,'col'=>3],['row'=>2,'col'=>4]];
            $s_aPaylineCombo[3] = [['row'=>0,'col'=>0],['row'=>1,'col'=>1],['row'=>2,'col'=>2],['row'=>1,'col'=>3],['row'=>0,'col'=>4]];
            $s_aPaylineCombo[4] = [['row'=>2,'col'=>0],['row'=>1,'col'=>1],['row'=>0,'col'=>2],['row'=>1,'col'=>3],['row'=>2,'col'=>4]];
            $s_aPaylineCombo[5] = [['row'=>1,'col'=>0],['row'=>0,'col'=>1],['row'=>0,'col'=>2],['row'=>0,'col'=>3],['row'=>1,'col'=>4]];
            $s_aPaylineCombo[6] = [['row'=>1,'col'=>0],['row'=>2,'col'=>1],['row'=>2,'col'=>2],['row'=>2,'col'=>3],['row'=>1,'col'=>4]];
            $s_aPaylineCombo[7] = [['row'=>0,'col'=>0],['row'=>0,'col'=>1],['row'=>1,'col'=>2],['row'=>2,'col'=>3],['row'=>2,'col'=>4]];
            $s_aPaylineCombo[8] = [['row'=>2,'col'=>0],['row'=>2,'col'=>1],['row'=>1,'col'=>2],['row'=>0,'col'=>3],['row'=>0,'col'=>4]];
            $s_aPaylineCombo[9] = [['row'=>1,'col'=>0],['row'=>2,'col'=>1],['row'=>1,'col'=>2],['row'=>0,'col'=>3],['row'=>1,'col'=>4]];
            $s_aPaylineCombo[10] = [['row'=>2,'col'=>0],['row'=>0,'col'=>1],['row'=>1,'col'=>2],['row'=>2,'col'=>3],['row'=>1,'col'=>4]];
            $s_aPaylineCombo[11] = [['row'=>0,'col'=>0],['row'=>1,'col'=>1],['row'=>1,'col'=>2],['row'=>1,'col'=>3],['row'=>0,'col'=>4]];
            $s_aPaylineCombo[12] = [['row'=>2,'col'=>0],['row'=>1,'col'=>1],['row'=>1,'col'=>2],['row'=>1,'col'=>3],['row'=>2,'col'=>4]];
            $s_aPaylineCombo[13] = [['row'=>0,'col'=>0],['row'=>1,'col'=>1],['row'=>0,'col'=>2],['row'=>1,'col'=>3],['row'=>0,'col'=>4]];
            $s_aPaylineCombo[14] = [['row'=>2,'col'=>0],['row'=>1,'col'=>1],['row'=>2,'col'=>2],['row'=>1,'col'=>3],['row'=>2,'col'=>4]];
            $s_aPaylineCombo[15] = [['row'=>1,'col'=>0],['row'=>1,'col'=>1],['row'=>0,'col'=>2],['row'=>1,'col'=>3],['row'=>1,'col'=>4]];
            $s_aPaylineCombo[16] = [['row'=>1,'col'=>0],['row'=>1,'col'=>1],['row'=>2,'col'=>2],['row'=>1,'col'=>3],['row'=>1,'col'=>4]];
            $s_aPaylineCombo[17] = [['row'=>0,'col'=>0],['row'=>0,'col'=>1],['row'=>2,'col'=>2],['row'=>0,'col'=>3],['row'=>0,'col'=>4]];
            $s_aPaylineCombo[18] = [['row'=>2,'col'=>0],['row'=>2,'col'=>1],['row'=>0,'col'=>2],['row'=>2,'col'=>3],['row'=>2,'col'=>4]];
            $s_aPaylineCombo[19] = [['row'=>0,'col'=>0],['row'=>2,'col'=>1],['row'=>2,'col'=>2],['row'=>2,'col'=>3],['row'=>0,'col'=>4]];

            $symbols = array();

            //OCCURENCE FOR SYMBOL 1
            for($i=0;$i<1;$i++){
                array_push($symbols, 1);
            }

            //OCCURENCE FOR SYMBOL 2
            for($i=0;$i<2;$i++){
                array_push($symbols, 2);
            }

            //OCCURENCE FOR SYMBOL 3
            for($i=0;$i<3;$i++){
                array_push($symbols, 3);
            }

            //OCCURENCE FOR SYMBOL 4
            for($i=0;$i<4;$i++){
                array_push($symbols, 4);
            }

            //OCCURENCE FOR SYMBOL 5
            for($i=0;$i<4;$i++){
                array_push($symbols, 5);
            }

            //OCCURENCE FOR SYMBOL 6
            for($i=0;$i<6;$i++){
                array_push($symbols, 6);
            }

            //OCCURENCE FOR SYMBOL 7
            for($i=0;$i<6;$i++){
                array_push($symbols, 7);
            }

            //OCCURENCE FOR SYMBOL WILD
            for($i=0;$i<1;$i++){
                array_push($symbols, GamesController::SLOT_WILD_SYMBOL);
            }

            $outcome = $this->__slotPlay($userId, $lines, $s_aPaylineCombo, $symbols, $settings, 'soccer', $bet, $hold, false, 20);

            $this->__slotCalculateWin($userId, $outcome, 0, 'soccer', $lines, $bet);

            // return variables to frontend
            $response = json_encode([$outcome]);
            $this->layout = 'ajax';
            $this->render(false);
            echo $response;
        } else {
            throw new Exception('Not found!', 404);
        }
    }

    public function slotSoccer3dPlay()
    {
        if ($this->Auth->user()) {
            if (!$this->request->data) {
                throw new Exception('Not found!', 404);
            }
            $request = json_decode($this->request->data);
            if (!is_numeric($request->{'amount'})) {
                throw new Exception('Not found!', 404);
            }
            if ($request->{'lines'} < 1 && $request->{'lines'} > 20) {
                throw new Exception('Not found!', 404);
            }
            $this->__initRedis();
            $userId = $this->Auth->user('id');
            $lines = floatval($request->{'lines'});
            $bet = round(floatval($request->{'amount'}), 2);
            $bet *= $lines;
            $options['conditions'] = array('User.id' => $userId);
            $balance = floatval($this->User->find('first', $options)["User"]["balance"]);
            // check if bet is lower than user balance or is 0
            if ($balance < $bet || $bet == 0) {
                throw new Exception('Not found!', 404);
            }
            $settings = $this->GameSetting->getSlotSoccer3dSettings();

            $hold = [false, false, false, false, false];

            if (!$this->redis->exists("{$userId}:slots-free-spins")) {
                // write off bet from player balance
                $this->User->addFunds($userId, (-1 * abs($bet)));
            } else {
                $freespinCount = intval($this->redis->get($userId.':slots-free-spins'));
                if ($freespinCount > 0) {
                    $freespinCount--;
                    $this->redis->set($userId.':slots-free-spins', $freespinCount);
                } else {
                    $this->User->addFunds($userId, (-1 * abs($bet)));
                }
            }
            // START GAME

            $s_aPaylineCombo[0] = [['row'=>1,'col'=>0],['row'=>1,'col'=>1],['row'=>1,'col'=>2],['row'=>1,'col'=>3],['row'=>1,'col'=>4]];
            $s_aPaylineCombo[1] = [['row'=>0,'col'=>0],['row'=>0,'col'=>1],['row'=>0,'col'=>2],['row'=>0,'col'=>3],['row'=>0,'col'=>4]];
            $s_aPaylineCombo[2] = [['row'=>2,'col'=>0],['row'=>2,'col'=>1],['row'=>2,'col'=>2],['row'=>2,'col'=>3],['row'=>2,'col'=>4]];
            $s_aPaylineCombo[3] = [['row'=>0,'col'=>0],['row'=>1,'col'=>1],['row'=>2,'col'=>2],['row'=>1,'col'=>3],['row'=>0,'col'=>4]];
            $s_aPaylineCombo[4] = [['row'=>2,'col'=>0],['row'=>1,'col'=>1],['row'=>0,'col'=>2],['row'=>1,'col'=>3],['row'=>2,'col'=>4]];
            $s_aPaylineCombo[5] = [['row'=>1,'col'=>0],['row'=>1,'col'=>1],['row'=>2,'col'=>2],['row'=>1,'col'=>3],['row'=>1,'col'=>4]];
            $s_aPaylineCombo[6] = [['row'=>1,'col'=>0],['row'=>1,'col'=>1],['row'=>0,'col'=>2],['row'=>1,'col'=>3],['row'=>1,'col'=>4]];
            $s_aPaylineCombo[7] = [['row'=>2,'col'=>0],['row'=>1,'col'=>1],['row'=>1,'col'=>2],['row'=>1,'col'=>3],['row'=>2,'col'=>4]];
            $s_aPaylineCombo[8] = [['row'=>0,'col'=>0],['row'=>1,'col'=>1],['row'=>1,'col'=>2],['row'=>1,'col'=>3],['row'=>0,'col'=>4]];
            $s_aPaylineCombo[9] = [['row'=>1,'col'=>0],['row'=>0,'col'=>1],['row'=>1,'col'=>2],['row'=>0,'col'=>3],['row'=>1,'col'=>4]];
            $s_aPaylineCombo[10] = [['row'=>1,'col'=>0],['row'=>2,'col'=>1],['row'=>1,'col'=>2],['row'=>2,'col'=>3],['row'=>1,'col'=>4]];
            $s_aPaylineCombo[11] = [['row'=>0,'col'=>0],['row'=>1,'col'=>1],['row'=>0,'col'=>2],['row'=>1,'col'=>3],['row'=>0,'col'=>4]];
            $s_aPaylineCombo[12] = [['row'=>2,'col'=>0],['row'=>1,'col'=>1],['row'=>2,'col'=>2],['row'=>1,'col'=>3],['row'=>2,'col'=>4]];
            $s_aPaylineCombo[13] = [['row'=>0,'col'=>0],['row'=>0,'col'=>1],['row'=>1,'col'=>2],['row'=>0,'col'=>3],['row'=>0,'col'=>4]];
            $s_aPaylineCombo[14] = [['row'=>2,'col'=>0],['row'=>2,'col'=>1],['row'=>1,'col'=>2],['row'=>2,'col'=>3],['row'=>2,'col'=>4]];
            $s_aPaylineCombo[15] = [['row'=>2,'col'=>0],['row'=>2,'col'=>1],['row'=>1,'col'=>2],['row'=>0,'col'=>3],['row'=>0,'col'=>4]];
            $s_aPaylineCombo[16] = [['row'=>0,'col'=>0],['row'=>0,'col'=>1],['row'=>1,'col'=>2],['row'=>2,'col'=>3],['row'=>2,'col'=>4]];
            $s_aPaylineCombo[17] = [['row'=>0,'col'=>0],['row'=>0,'col'=>1],['row'=>2,'col'=>2],['row'=>0,'col'=>3],['row'=>0,'col'=>4]];
            $s_aPaylineCombo[18] = [['row'=>2,'col'=>0],['row'=>2,'col'=>1],['row'=>0,'col'=>2],['row'=>2,'col'=>3],['row'=>2,'col'=>4]];
            $s_aPaylineCombo[19] = [['row'=>0,'col'=>0],['row'=>2,'col'=>1],['row'=>2,'col'=>2],['row'=>2,'col'=>3],['row'=>0,'col'=>4]];

            $symbols = array();

            //OCCURENCE FOR SYMBOL 1
            for($i=0;$i<2;$i++){
                array_push($symbols, 1);
            }

            //OCCURENCE FOR SYMBOL 2
            for($i=0;$i<3;$i++){
                array_push($symbols, 2);
            }

            //OCCURENCE FOR SYMBOL 3
            for($i=0;$i<4;$i++){
                array_push($symbols, 3);
            }

            //OCCURENCE FOR SYMBOL 4
            for($i=0;$i<4;$i++){
                array_push($symbols, 4);
            }

            //OCCURENCE FOR SYMBOL 5
            for($i=0;$i<6;$i++){
                array_push($symbols, 5);
            }

            //OCCURENCE FOR SYMBOL 6
            for($i=0;$i<6;$i++){
                array_push($symbols, 6);
            }

            //OCCURENCE FOR SYMBOL 7
            for($i=0;$i<6;$i++){
                array_push($symbols, 7);
            }

            //OCCURENCE FOR SYMBOL 8
            for($i=0;$i<6;$i++){
                array_push($symbols, 8);
            }

            //OCCURENCE FOR SYMBOL 9
            for($i=0;$i<6;$i++){
                array_push($symbols, 11);
            }

            //OCCURENCE FOR SYMBOL 10 (10 is reserved for wild, replace in JS)
            for($i=0;$i<6;$i++){
                array_push($symbols, 12);
            }

            //OCCURENCE FOR SYMBOL WILD
            for($i=0;$i<1;$i++){
                array_push($symbols, GamesController::SLOT_WILD_SYMBOL);
            }

            //OCCURENCE FOR SYMBOL BONUS
            for($i=0;$i<2;$i++){
                array_push($symbols, GamesController::SLOT_BONUS_SYMBOL);
            }

            //OCCURENCE FOR SYMBOL FREESPIN
            for($i=0;$i<2;$i++){
                array_push($symbols, 13);
            }

            $outcome = $this->__slotPlay($userId, $lines, $s_aPaylineCombo, $symbols, $settings, 'soccer3d', $bet, $hold, false, 20);

            // BONUS GAME
            $bonus = 0;
            $bonusIndex = 0;
            if ($outcome[1] === true) {
                $bonusArray = $this->__slotBonus($outcome[5], $settings, 'soccer3d');
                $bonus = $bonusArray[0];
                $bonusIndex = $bonusArray[1];
            }

            $freespins = $this->__slotFreeSpins($outcome[3], $settings, 'soccer3d', $userId, $outcome[2]);
            $outcome[2] = $freespins[1];

            $this->__slotCalculateWin($userId, $outcome, $bonus, 'soccer3d', $lines, $bet);

            // return variables to frontend
            $response = json_encode([$outcome, $bonusIndex, $freespins[0]]);
            $this->layout = 'ajax';
            $this->render(false);
            echo $response;
        } else {
            throw new Exception('Not found!', 404);
        }
    }

    public function slotArabianPlay()
    {
        if ($this->Auth->user()) {
            if (!$this->request->data) {
                throw new Exception('Not found!', 404);
            }
            $request = json_decode($this->request->data);
            if (!is_numeric($request->{'amount'})) {
                throw new Exception('Not found!', 404);
            }
            if ($request->{'lines'} < 1 && $request->{'lines'} > 20) {
                throw new Exception('Not found!', 404);
            }
            $this->__initRedis();
            $userId = $this->Auth->user('id');
            $lines = floatval($request->{'lines'});
            $bet = round(floatval($request->{'amount'}), 2);
            $bet *= $lines;
            $options['conditions'] = array('User.id' => $userId);
            $balance = floatval($this->User->find('first', $options)["User"]["balance"]);
            // check if bet is lower than user balance or is 0
            if ($balance < $bet || $bet == 0) {
                throw new Exception('Not found!', 404);
            }
            $settings = $this->GameSetting->getSlotArabianSettings();

            $hold = [false, false, false, false, false];

            if (!$this->redis->exists("{$userId}:slots-free-spins")) {
                // write off bet from player balance
                $this->User->addFunds($userId, (-1 * abs($bet)));
            } else {
                $freespinCount = intval($this->redis->get($userId.':slots-free-spins'));
                if ($freespinCount > 0) {
                    $freespinCount--;
                    $this->redis->set($userId.':slots-free-spins', $freespinCount);
                } else {
                    $this->User->addFunds($userId, (-1 * abs($bet)));
                }
            }
            // START GAME

            $s_aPaylineCombo[0] = [['row'=>1,'col'=>0],['row'=>1,'col'=>1],['row'=>1,'col'=>2],['row'=>1,'col'=>3],['row'=>1,'col'=>4]];
            $s_aPaylineCombo[1] = [['row'=>0,'col'=>0],['row'=>0,'col'=>1],['row'=>0,'col'=>2],['row'=>0,'col'=>3],['row'=>0,'col'=>4]];
            $s_aPaylineCombo[2] = [['row'=>2,'col'=>0],['row'=>2,'col'=>1],['row'=>2,'col'=>2],['row'=>2,'col'=>3],['row'=>2,'col'=>4]];
            $s_aPaylineCombo[3] = [['row'=>0,'col'=>0],['row'=>1,'col'=>1],['row'=>2,'col'=>2],['row'=>1,'col'=>3],['row'=>0,'col'=>4]];
            $s_aPaylineCombo[4] = [['row'=>2,'col'=>0],['row'=>1,'col'=>1],['row'=>0,'col'=>2],['row'=>1,'col'=>3],['row'=>2,'col'=>4]];
            $s_aPaylineCombo[5] = [['row'=>1,'col'=>0],['row'=>0,'col'=>1],['row'=>0,'col'=>2],['row'=>0,'col'=>3],['row'=>1,'col'=>4]];
            $s_aPaylineCombo[6] = [['row'=>1,'col'=>0],['row'=>2,'col'=>1],['row'=>2,'col'=>2],['row'=>2,'col'=>3],['row'=>1,'col'=>4]];
            $s_aPaylineCombo[7] = [['row'=>0,'col'=>0],['row'=>0,'col'=>1],['row'=>1,'col'=>2],['row'=>2,'col'=>3],['row'=>2,'col'=>4]];
            $s_aPaylineCombo[8] = [['row'=>2,'col'=>0],['row'=>2,'col'=>1],['row'=>1,'col'=>2],['row'=>0,'col'=>3],['row'=>0,'col'=>4]];
            $s_aPaylineCombo[9] = [['row'=>1,'col'=>0],['row'=>2,'col'=>1],['row'=>1,'col'=>2],['row'=>0,'col'=>3],['row'=>1,'col'=>4]];
            $s_aPaylineCombo[10] = [['row'=>2,'col'=>0],['row'=>0,'col'=>1],['row'=>1,'col'=>2],['row'=>2,'col'=>3],['row'=>1,'col'=>4]];
            $s_aPaylineCombo[11] = [['row'=>0,'col'=>0],['row'=>1,'col'=>1],['row'=>1,'col'=>2],['row'=>1,'col'=>3],['row'=>0,'col'=>4]];
            $s_aPaylineCombo[12] = [['row'=>2,'col'=>0],['row'=>1,'col'=>1],['row'=>1,'col'=>2],['row'=>1,'col'=>3],['row'=>2,'col'=>4]];
            $s_aPaylineCombo[13] = [['row'=>0,'col'=>0],['row'=>1,'col'=>1],['row'=>0,'col'=>2],['row'=>1,'col'=>3],['row'=>0,'col'=>4]];
            $s_aPaylineCombo[14] = [['row'=>2,'col'=>0],['row'=>1,'col'=>1],['row'=>2,'col'=>2],['row'=>1,'col'=>3],['row'=>2,'col'=>4]];
            $s_aPaylineCombo[15] = [['row'=>1,'col'=>0],['row'=>1,'col'=>1],['row'=>0,'col'=>2],['row'=>1,'col'=>3],['row'=>1,'col'=>4]];
            $s_aPaylineCombo[16] = [['row'=>1,'col'=>0],['row'=>1,'col'=>1],['row'=>2,'col'=>2],['row'=>1,'col'=>3],['row'=>1,'col'=>4]];
            $s_aPaylineCombo[17] = [['row'=>0,'col'=>0],['row'=>0,'col'=>1],['row'=>2,'col'=>2],['row'=>0,'col'=>3],['row'=>0,'col'=>4]];
            $s_aPaylineCombo[18] = [['row'=>2,'col'=>0],['row'=>2,'col'=>1],['row'=>0,'col'=>2],['row'=>2,'col'=>3],['row'=>2,'col'=>4]];
            $s_aPaylineCombo[19] = [['row'=>0,'col'=>0],['row'=>2,'col'=>1],['row'=>2,'col'=>2],['row'=>2,'col'=>3],['row'=>0,'col'=>4]];

            $symbols = array();

            //OCCURENCE FOR SYMBOL 1
            for($i=0;$i<1;$i++){
                array_push($symbols, 1);
            }

            //OCCURENCE FOR SYMBOL 2
            for($i=0;$i<2;$i++){
                array_push($symbols, 2);
            }

            //OCCURENCE FOR SYMBOL 3
            for($i=0;$i<3;$i++){
                array_push($symbols, 3);
            }

            //OCCURENCE FOR SYMBOL 4
            for($i=0;$i<4;$i++){
                array_push($symbols, 4);
            }

            //OCCURENCE FOR SYMBOL 5
            for($i=0;$i<5;$i++){
                array_push($symbols, 5);
            }

            //OCCURENCE FOR SYMBOL 6
            for($i=0;$i<6;$i++){
                array_push($symbols, 6);
            }

            //OCCURENCE FOR SYMBOL 7
            for($i=0;$i<7;$i++){
                array_push($symbols, 7);
            }

            //OCCURENCE FOR SYMBOL WILD
            for($i=0;$i<1;$i++){
                array_push($symbols, GamesController::SLOT_WILD_SYMBOL);
            }

            //OCCURENCE FOR SYMBOL BONUS
            for($i=0;$i<2;$i++){
                array_push($symbols, GamesController::SLOT_BONUS_SYMBOL);
            }

            //OCCURENCE FOR SYMBOL FREESPIN
            for($i=0;$i<2;$i++){
                array_push($symbols, 13);
            }

            $outcome = $this->__slotPlay($userId, $lines, $s_aPaylineCombo, $symbols, $settings, 'arabian', $bet, $hold, false, 20);

            // BONUS GAME
            $bonus = 0;
            $bonusIndex = 0;
            if ($outcome[1] === true) {
                $bonusArray = $this->__slotBonus($outcome[5], $settings, 'arabian');
                $bonus = $bonusArray[0];
                $bonusIndex = $bonusArray[1];
            }

            $freespins = $this->__slotFreeSpins($outcome[3], $settings, 'arabian', $userId, $outcome[2]);
            $outcome[2] = $freespins[1];

            $this->__slotCalculateWin($userId, $outcome, $bonus, 'arabian', $lines, $bet);

            // return variables to frontend
            $response = json_encode([$outcome, $bonusIndex, $freespins[0]]);
            $this->layout = 'ajax';
            $this->render(false);
            echo $response;
        } else {
            throw new Exception('Not found!', 404);
        }
    }

    protected function __slotFreeSpins($combo, $settings, $gameName, $userId, $winLine) {
	    $count = 0;
	    $list = [];
        for($i=0;$i<3;$i++){
            for($j=0;$j<5;$j++){
                if ($combo[$i][$j] === 13) {
                    array_push($list, ['row'=>$i,'col'=>$j,'value'=>13]);
                    $count++;
                }
            }
        }

        if ($count > 2) {
            $s_aBonusItemOccurence = array();
            for ($i = 0; $i < $settings['slot_' . $gameName . '_freespin_occurence_1']['value']; $i++) {
                array_push($s_aBonusItemOccurence, 1);
            }

            for ($i = 0; $i < $settings['slot_' . $gameName . '_freespin_occurence_2']['value']; $i++) {
                array_push($s_aBonusItemOccurence, 2);
            }

            for ($i = 0; $i < $settings['slot_' . $gameName . '_freespin_occurence_3']['value']; $i++) {
                array_push($s_aBonusItemOccurence, 3);
            }

            $iRandItem = floor((float)rand() / (float)getrandmax() * count($s_aBonusItemOccurence));
            $index = $s_aBonusItemOccurence[$iRandItem];
            $freespins = intval($settings['slot_' . $gameName . '_freespin_' . $index]['value']);

            if ($this->redis->exists("{$userId}:slots-free-spins")) {
                $freespinCount = intval($this->redis->get($userId.':slots-free-spins'));
                $freespinCount += $freespins;
                $this->redis->set($userId.':slots-free-spins', $freespinCount);
            } else {
                $this->redis->set($userId.':slots-free-spins', $freespins);
            }

            array_push($winLine, ['line'=>0,'amount'=>0,'num_win'=>$count,'value'=>13,'list'=>$list]);

            return [$freespins, $winLine];

        } else {
            return [0, $winLine];
        }
    }

	protected function __slotBonus($_iNumItemInBonus, $settings, $gameName)
    {

        if ($gameName === 'arabian') {
            $s_aBonusItemOccurence = array();
            $bonus = array_map('intval', explode(',', $settings['slot_arabian_bonus_occurence_all']['value']));

            for ($i=0; $i < count($bonus); $i++) {
                for ($j=0; $j < $bonus[$i]; $j++) {
                    array_push($s_aBonusItemOccurence, $i);
                }
            }

            $iRandItem = floor((float)rand() / (float)getrandmax() * count($s_aBonusItemOccurence));
            $index = $s_aBonusItemOccurence[$iRandItem];
            $bonusPrize = array_map('intval', explode(',', $settings['slot_arabian_bonus']['value']));
            return [$bonusPrize[$index], $index];
        }

        $s_aBonusItemOccurence = array();
        for ($i = 0; $i < $settings['slot_' . $gameName . '_bonus_occurence_1']['value']; $i++) {
            array_push($s_aBonusItemOccurence, 0);
        }

        for ($i = 0; $i < $settings['slot_' . $gameName . '_bonus_occurence_2']['value']; $i++) {
            array_push($s_aBonusItemOccurence, 1);
        }

        for ($i = 0; $i < $settings['slot_' . $gameName . '_bonus_occurence_3']['value']; $i++) {
            array_push($s_aBonusItemOccurence, 2);
        }

        if ($gameName === 'soccer3d') {
            for ($i = 0; $i < $settings['slot_' . $gameName . '_bonus_occurence_4']['value']; $i++) {
                array_push($s_aBonusItemOccurence, 3);
            }
            for ($i = 0; $i < $settings['slot_' . $gameName . '_bonus_occurence_5']['value']; $i++) {
                array_push($s_aBonusItemOccurence, 4);
            }
        }

        $iRandItem = floor((float)rand() / (float)getrandmax() * count($s_aBonusItemOccurence));

        $bonus = 0;

        if ($gameName === 'soccer3d') {
            $index = $s_aBonusItemOccurence[$iRandItem];
            $bonus = intval($settings['slot_' . $gameName . '_bonus_' . ($index+1)]['value']);
            return [$bonus, $s_aBonusItemOccurence[$iRandItem]];
        } else {
            if ($_iNumItemInBonus === 3) {
                $bonus = $settings['slot_' . $gameName . '_bonus_1']['value'];
                $bonus = array_map('intval', explode(',', $bonus));
                $bonus = $bonus[$s_aBonusItemOccurence[$iRandItem]];
            } else if ($_iNumItemInBonus === 4) {
                $bonus = $settings['slot_' . $gameName . '_bonus_2']['value'];
                $bonus = array_map('intval', explode(',', $bonus));
                $bonus = $bonus[$s_aBonusItemOccurence[$iRandItem]];
            } else if ($_iNumItemInBonus === 5) {
                $bonus = $settings['slot_' . $gameName . '_bonus_3']['value'];
                $bonus = array_map('intval', explode(',', $bonus));
                $bonus = $bonus[$s_aBonusItemOccurence[$iRandItem]];
            }
            return [$bonus, $s_aBonusItemOccurence[$iRandItem]];
        }
	}

	protected function __slotRedisUnset($userId){
		$this->redis->del($userId.':slots-hold-available');
		$this->redis->del($userId.':slots-symbol-combo');
        $this->redis->del($userId.':slots-free-spins');
	}

	protected function __slotPlay($userId, $lines, $s_aPaylineCombo, $symbols, $settings, $gameName, $bet, $hold, $holdAvailable, $lineCount){
		$_aFinalSymbolCombo = array();
		if (!$this->redis->exists("{$userId}:slots-hold-available") || !$holdAvailable) {
			for($i=0;$i<3;$i++){
				$_aFinalSymbolCombo[$i] = array();
				for($j=0;$j<5;$j++){
					$_aFinalSymbolCombo[$i][$j] = 0;
				}
			}
		} else {
			$_aFinalSymbolCombo = unserialize($this->redis->get($userId.':slots-symbol-combo'));
		}

		//RANDOM TO ASSIGN A WIN OR NOT
		$WIN_OCCURRENCE = $settings['slot_'.$gameName.'_win_occurence']['value']/$lineCount*$lines;
		$iRandSpin = (float)rand()/(float)getrandmax()*100;
		$bRet = $this->__slotFinalSymbols($lines, $s_aPaylineCombo, $symbols, $_aFinalSymbolCombo, $settings, $gameName, $bet, $hold);
		if($iRandSpin > $WIN_OCCURRENCE){
			//PLAYER LOSES
			do{
				$bRet = $this->__slotFinalSymbols($lines, $s_aPaylineCombo, $symbols, $_aFinalSymbolCombo, $settings, $gameName, $bet, $hold);
			}while($bRet[0] === true || $bRet[1]);
		}else{
			//PLAYER WINS
			$iRandBonus = (float)rand()/(float)getrandmax()*100;
			if (intval($this->redis->get($userId.':slots-free-spins') > 0)) {
                $iRandBonus = 101;
            }
			if($iRandBonus >= ($settings['slot_'.$gameName.'_bonus_occurence']['value']/$lineCount*$lines)){
				//NO BONUS
				$iCont = 0;
				do{
						$bRet = $this->__slotFinalSymbols($lines, $s_aPaylineCombo, $symbols, $_aFinalSymbolCombo, $settings, $gameName, $bet, $hold);
						$iCont++;
				}while( ($bRet[0] === false || ($bRet[4]*$bet/$lines) > $settings['slot_'.$gameName.'_auto_lose_threshold']['value'] || $bRet[1]) && $iCont <= 100 );

				if($iCont > 100){
					//PLAYER MUST LOSE
					do{
						$bRet = $this->__slotFinalSymbols($lines, $s_aPaylineCombo, $symbols, $_aFinalSymbolCombo, $settings, $gameName, $bet, $hold);
					}while($bRet[0] === true || $bRet[1]);
				}
			}else{
				//GET A BONUS
				$iCont = 0;
				do{
					$bRet = $this->__slotFinalSymbols($lines, $s_aPaylineCombo, $symbols, $_aFinalSymbolCombo, $settings, $gameName, $bet, $hold);
					$iIndex = 0;
					if($bRet[1]){
						$iIndex = $bRet[5] - 3;
					}
					$iCont++;
                    $count = 0;
                    for($i=0;$i<3;$i++){
                        for($j=0;$j<5;$j++){
                            if ($bRet[3][$i][$j] === 13) {
                                $count++;
                            }
                        }
                    }
				}while( ($bRet[0] === false || $count > 2 || (($bRet[4]*$bet/$lines)+(10 * $bet/$lines)) > $settings['slot_'.$gameName.'_auto_lose_threshold']['value'] || $bRet[1] === false) && $iCont <= 1000);

				if($iCont > 1000){
					//PLAYER MUST LOSE
					do{
						$bRet = $this->__slotFinalSymbols($lines, $s_aPaylineCombo, $symbols, $_aFinalSymbolCombo, $settings, $gameName, $bet, $hold);
					}while($bRet[0] === true || $bRet[1]);
				}
			}
		}

		if ($bRet[0] === false && $holdAvailable) {
			if ($this->redis->exists("{$userId}:slots-hold-available")) {
				$this->redis->del($userId.':slots-hold-available');
				$this->redis->del($userId.':slots-symbol-combo');
			} else {
				$this->redis->set($userId.':slots-hold-available', 1);
				$this->redis->set($userId.':slots-symbol-combo', serialize($bRet[3]));
			}
		} else if ($holdAvailable) {
			$this->redis->del($userId.':slots-hold-available');
			$this->redis->del($userId.':slots-symbol-combo');
		}

		return $bRet;
	}

	protected function __slotInitSymbols() {
		$s_aRandSymbols = array();
        //OCCURENCE FOR SYMBOL 1
        for($i=0;$i<1;$i++){
			array_push($s_aRandSymbols, 1);
        }

        //OCCURENCE FOR SYMBOL 2
        for($i=0;$i<2;$i++){
            array_push($s_aRandSymbols, 2);
        }

        //OCCURENCE FOR SYMBOL 3
        for($i=0;$i<3;$i++){
            array_push($s_aRandSymbols, 3);
        }

        //OCCURENCE FOR SYMBOL 4
        for($i=0;$i<4;$i++){
            array_push($s_aRandSymbols, 4);
        }

        //OCCURENCE FOR SYMBOL 5
        for($i=0;$i<4;$i++){
            array_push($s_aRandSymbols, 5);
        }

        //OCCURENCE FOR SYMBOL 6
        for($i=0;$i<6;$i++){
            array_push($s_aRandSymbols, 6);
        }

        //OCCURENCE FOR SYMBOL 7
        for($i=0;$i<7;$i++){
            array_push($s_aRandSymbols, 7);
        }

        //OCCURENCE FOR SYMBOL 8
        for($i=0;$i<8;$i++){
            array_push($s_aRandSymbols, 8);
        }

        //OCCURENCE FOR SYMBOL 9. this is bonus symbol
        for($i=0;$i<2;$i++){
            array_push($s_aRandSymbols, GamesController::SLOT_BONUS_SYMBOL);
        }

        //OCCURENCE FOR SYMBOL WILD
        for($i=0;$i<1;$i++){
            array_push($s_aRandSymbols, GamesController::SLOT_WILD_SYMBOL);
        }

		return $s_aRandSymbols;
	}

	protected function __slotFinalSymbols($lines, $s_aPaylineCombo, $s_aRandSymbols, $_aFinalSymbolCombo, $settings, $gameName, $bet, $hold) {
		for($i=0;$i<3;$i++){
            for($j=0;$j<5;$j++){
				if ($hold[$j] === false) {
					$iRandIndex = floor((float)rand()/(float)getrandmax()*count($s_aRandSymbols));
                    $iRandSymbol = $s_aRandSymbols[$iRandIndex];
                    $_aFinalSymbolCombo[$i][$j] = $iRandSymbol;
                }
            }
        }

        $combos = $this->__slotCheckForCombos($lines, $s_aPaylineCombo, $_aFinalSymbolCombo, $settings, $gameName, $bet);
		$bWin = $combos[0];
		$_aWinningLine = $combos[1];
		$_iTotWin = $combos[2];
		$bonus = $this->__slotCheckForBonus($_aFinalSymbolCombo, $_aWinningLine);
        $_bBonus = $bonus[0];
		$_aWinningLine = $bonus[1];
		$_iNumItemInBonus = $bonus[2];

        return [$bWin, $_bBonus, $_aWinningLine, $_aFinalSymbolCombo, $_iTotWin, $_iNumItemInBonus];
	}

	protected function __slotCheckForCombos($_iLastLineActive, $s_aPaylineCombo, $_aFinalSymbolCombo, $settings, $gameName, $bet) {
		//CHECK IF THERE IS ANY COMBO

        $_aWinningLine = array();
        $_iTotWin = 0;
        for($k=0;$k<$_iLastLineActive;$k++){
            $aCombos = $s_aPaylineCombo[$k];

            $aCellList = array();
            $iValue = $_aFinalSymbolCombo[$aCombos[0]['row']][$aCombos[0]['col']];
            if($iValue !== GamesController::SLOT_BONUS_SYMBOL){ //bonus symbol
                $iNumEqualSymbol = 1;
                $iStartIndex = 1;
				array_push($aCellList, ['row'=>$aCombos[0]['row'],'col'=>$aCombos[0]['col'],'value'=>$_aFinalSymbolCombo[$aCombos[0]['row']][$aCombos[0]['col']]]);

                while($iValue === GamesController::SLOT_WILD_SYMBOL && $iStartIndex<5){
                    $iNumEqualSymbol++;
                    $iValue = $_aFinalSymbolCombo[$aCombos[$iStartIndex]['row']][$aCombos[$iStartIndex]['col']];
					array_push($aCellList, ['row'=>$aCombos[$iStartIndex]['row'],'col'=>$aCombos[$iStartIndex]['col'],'value'=>$_aFinalSymbolCombo[$aCombos[$iStartIndex]['row']][$aCombos[$iStartIndex]['col']]]);
                    $iStartIndex++;
                }

                for($t=$iStartIndex;$t<count($aCombos);$t++){
                    if($_aFinalSymbolCombo[$aCombos[$t]['row']][$aCombos[$t]['col']] === $iValue || $_aFinalSymbolCombo[$aCombos[$t]['row']][$aCombos[$t]['col']] === GamesController::SLOT_WILD_SYMBOL){
                        if($_aFinalSymbolCombo[$aCombos[$t]['row']][$aCombos[$t]['col']] === GamesController::SLOT_BONUS_SYMBOL){ //bonus symbol
                            break;
                        }
                        $iNumEqualSymbol++;

						array_push($aCellList, ['row'=>$aCombos[$t]['row'],'col'=>$aCombos[$t]['col'],'value'=>$_aFinalSymbolCombo[$aCombos[$t]['row']][$aCombos[$t]['col']]]);
                    }else{
                        break;
                    }
                }

				if ($iValue !== GamesController::SLOT_BONUS_SYMBOL && $iValue !== 13) {
				    $bonus = null;
				    if ($iValue === 11) {
                        $bonus = $settings['slot_'.$gameName.'_paytable_9']['value'];
                    } else if ($iValue === 12) {
                        $bonus = $settings['slot_'.$gameName.'_paytable_10']['value'];
                    } else {
                        $bonus = $settings['slot_'.$gameName.'_paytable_'.$iValue]['value'];
                    }
					$bonus = array_map('intval', explode(',', $bonus));
					if($iValue !== GamesController::SLOT_BONUS_SYMBOL && $bonus[$iNumEqualSymbol-1] > 0){
						$_iTotWin += $bonus[$iNumEqualSymbol-1];
						array_push($_aWinningLine, ['line'=>($k+1),'amount'=>$bonus[$iNumEqualSymbol-1],'num_win'=>$iNumEqualSymbol,'value'=>$iValue,'list'=>$aCellList]);
					}
				}
            }
        }

        return [$_iTotWin>$bet ? true : false, $_aWinningLine, $_iTotWin];
	}

	protected function __slotCheckForBonus($_aFinalSymbolCombo, $_aWinningLine) {
		//CHECK IF THERE IS BONUS
        $_bBonus = false;
        $_iNumItemInBonus = 0;
        $aBonusSymbols = array();
        for($i=0;$i<3;$i++){
            for($j=0;$j<5;$j++){
                if($_aFinalSymbolCombo[$i][$j] === GamesController::SLOT_BONUS_SYMBOL){
					array_push($aBonusSymbols, ['row'=>$i,'col'=>$j,'value'=>$_aFinalSymbolCombo[$i][$j]]);
                    $_iNumItemInBonus++;
                }
            }
        }

        if($_iNumItemInBonus >= 3){
            array_push($_aWinningLine, ['line'=>-1,'amount'=>0,'num_win'=>$_iNumItemInBonus,'value'=>GamesController::SLOT_BONUS_SYMBOL,'list'=>$aBonusSymbols]);
            if($_iNumItemInBonus>5){
                $_iNumItemInBonus = 5;
            }

			return [true, $_aWinningLine, $_iNumItemInBonus];
        }
		return [false, $_aWinningLine, $_iNumItemInBonus];
	}

	protected function __slotCalculateWin($userId, $outcome, $bonus, $nameForLog, $lines, $bet) {
		if(count($outcome[2]) > 0){
			if ($this->redis->exists("{$userId}:slots-hold-available")) {
				$this->redis->del($userId.':slots-hold-available');
				$this->redis->del($userId.':slots-symbol-combo');
			}
            $win = $outcome[4];
			$_iTotWin = $win*$bet/$lines;
			$this->User->addFunds($userId, $_iTotWin);
            $this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> won <b style='color: green'>".$_iTotWin."$</b> in <b>Slot ".$nameForLog."</b>", 'Slot '.$nameForLog);
			if ($bonus > 0) {
				$_iTotBonus = $bonus*$bet/$lines;
				$this->User->addFunds($userId, $_iTotBonus);
				$this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> won <b style='color: green'>".$_iTotBonus."$</b> in <b>Slot ".$nameForLog."</b> on BONUS", 'Slot '.$nameForLog);
			}
        } else if (!$this->redis->exists("{$userId}:slots-hold-available")) {
            $this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> lost <b style='color: red'>".$bet."$</b> in <b>Slot ".$nameForLog."</b>", 'Slot '.$nameForLog);
        }
	}

    public function kenoInit()
    {
        if ($this->Auth->user()) {
            $settings = $this->GameSetting->getKenoSettings();
            $options['conditions'] = array('User.id' => $this->Auth->user('id'));
            $data = new \stdClass();
            $data->starting_money = floatval($this->User->find('first', $options)["User"]["balance"]);

            for($i=2; $i<11; $i++) {
                $paytable = $settings['keno_pays_'.$i]['value'];
                $paytable = array_map('intval', explode(',', $paytable));
                $name = 'pays_'.$i;
                $data->{$name} = $paytable;
            }

            $data->fullscreen = intval($settings['keno_fullscreen']['value']) == '1' ? true : false;
            $data->check_orientation = intval($settings['keno_check_orientation']['value']) == '1' ? true : false;
            $data->show_credits = intval($settings['keno_show_credits']['value']) == '1' ? true : false;
            $response = json_encode($data);
            $this->layout = 'ajax';
            $this->render(false);
            echo $response;
        } else {
            throw new Exception('Not found!', 404);
        }
    }

    public function kenoPlay()
    {
        if ($this->Auth->user()) {
            if (!$this->request->data) {
                throw new Exception('Not found!', 404);
            }
            $userId = $this->Auth->user('id');
            $options['conditions'] = array('User.id' => $userId);
            $balance = floatval($this->User->find('first', $options)["User"]["balance"]);

            $request = json_decode($this->request->data);
            $bet = round(floatval($request->{'bet'}), 2);
            $numbers = $request->{'numbers'};

            //Check if number amount are correct
            if (count($numbers) < 2 || count($numbers) > 10) {
                throw new Exception('Not found!', 404);
            }

            //Check if numbers sent are correct
            for ($i=0; $i < count($numbers); $i++) {
                if ($numbers[$i] < 0 || $numbers[$i] > 79) {
                    throw new Exception('Not found!', 404);
                    break;
                }
            }
            if (count($numbers) < 2 && count($numbers) > 10) {
                throw new Exception('Not found!', 404);
            }

            //Check if bet is higher than user balance or is 0
            if ($balance < $bet || $bet == 0) {
                throw new Exception('Not found!', 404);
            }
            $settings = $this->GameSetting->getKenoSettings();
            $WIN_OCCURRENCE = array_map('intval', explode(',', $settings['keno_win_occurence']['value']));
            array_unshift($WIN_OCCURRENCE, '-');

            $payouts = array();
            $payouts[0]['hits'] = '-';
            $payouts[0]['pays'] = '-';
            $payouts[0]['occurrence'] = [0];
            $payouts[1]['hits'] = [2, 1];
            $payouts[2]['hits'] = [3, 2];
            $payouts[3]['hits'] = [4, 3, 2];
            $payouts[4]['hits'] = [5, 4, 3];
            $payouts[5]['hits'] = [6, 5, 4, 3];
            $payouts[6]['hits'] = [7, 6, 5, 4, 3];
            $payouts[7]['hits'] = [8, 7, 6, 5, 4];
            $payouts[8]['hits'] = [9, 8, 7, 6, 5, 4];
            $payouts[9]['hits'] = [10, 9, 8, 7, 6, 5];
            for($i=1; $i<10; $i++) {
                $payouts[$i]['pays'] = $settings['keno_pays_'.($i+1)]['value'];
                $payouts[$i]['pays'] = array_map('intval', explode(',', $payouts[$i]['pays']));
                $payouts[$i]['occurrence'] = $settings['keno_occurence_'.($i+1)]['value'];
                $payouts[$i]['occurrence'] = array_map('intval', explode(',', $payouts[$i]['occurrence']));
            }

            $combination = null;
            $_iHitsNumber = null;
            $_bWin = false;
            $iRandWin = (float)rand()/(float)getrandmax()*100;

            $_aNumSelected = array_fill(0, 80, false);
            for ($i=0; $i < count($numbers); $i++) {
                $_aNumSelected[$numbers[$i]] = true;
            }

            if($iRandWin < $WIN_OCCURRENCE[count($numbers)-1]){

                $aWinOccurrenceList = array();
                for($i=count($payouts[count($numbers)-1]['pays'])-1; $i>=0; $i--){
                    for($j=0; $j<$payouts[count($numbers)-1]['occurrence'][$i]; $j++){
                        array_push($aWinOccurrenceList, $payouts[count($numbers)-1]['hits'][$i]);
                    }
                }

                $iRandWinIndex = floor((float)rand()/(float)getrandmax()*count($aWinOccurrenceList));

                //Copy win numbers
                $aWinTempList = array();
                for($i=0; $i<count($numbers); $i++){
                    $aWinTempList[$i] = $numbers[$i]+1;
                }
                shuffle($aWinTempList);

                //Copy lose numbers
                $aLoseTempList = array();
                for($i=0; $i<count($_aNumSelected); $i++){
                    if(!$_aNumSelected[$i]){
                        array_push($aLoseTempList, $i+1);
                    }
                }
                shuffle($aLoseTempList);

                //Extract combination
                $_aCombination = array();
                for($i=0; $i<20; $i++){
                    if($i<$aWinOccurrenceList[$iRandWinIndex]){
                        array_push($_aCombination, $aWinTempList[$i]);
                    } else {
                        array_push($_aCombination, $aLoseTempList[$i]);
                    }
                }
                shuffle($_aCombination);

                $combination = $_aCombination;
                $_iHitsNumber = $aWinOccurrenceList[$iRandWinIndex];
                $_bWin = true;
            } else {
                $iMaxFakeWinNumber = ($payouts[count($numbers)-1]['hits'][count($payouts[count($numbers)-1]['hits'])-1]) - 1;
                $iWinNumberToExtract = round((float)rand()/(float)getrandmax()*$iMaxFakeWinNumber);

                //Copy win numbers
                $aWinTempList = array();
                for($i=0; $i<count($numbers); $i++){
                    $aWinTempList[$i] = $numbers[$i]+1;
                }
                shuffle($aWinTempList);

                //Copy lose numbers
                $aLoseTempList = array();
                for($i=0; $i<count($_aNumSelected); $i++){
                    if(!$_aNumSelected[$i]){
                        array_push($aLoseTempList, $i+1);
                    }
                }
                shuffle($aLoseTempList);

                //Extract combination
                $_aCombination = array();
                for($i=0; $i<20; $i++){
                    if($i<$iWinNumberToExtract){
                        array_push($_aCombination, $aWinTempList[$i]);
                    } else {
                        array_push($_aCombination, $aLoseTempList[$i]);
                    }
                }
                shuffle($_aCombination);

                $combination = $_aCombination;
                $_iHitsNumber = 0;
                $_bWin = false;
            }

            if ($_bWin){
                $this->User->addFunds($userId, (-1 * abs($bet)));
                //Update Money
                for($i=0; $i<count($payouts[count($numbers)-1]['hits']); $i++){
                    if($payouts[count($numbers)-1]['hits'][$i] === $_iHitsNumber){
                        $iTotalWin = ($bet*$payouts[count($numbers)-1]['pays'][$i]);
                        $this->User->addFunds($userId, $iTotalWin);
                        break;
                    }
                }
                $this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> bet <b>".$bet."$</b> and won <b style='color: green'>".$iTotalWin."$</b> in <b>Keno</b>", 'Keno');
            } else {
                $this->User->addFunds($userId, (-1 * abs($bet)));
                $this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> bet <b>".$bet."$</b> and lost <b style='color: red'>".$bet."$</b> in <b>Keno</b>", 'Keno');

            }

            $data = new \stdClass();
            $data->combination = $_aCombination;
            $data->hitsNum = $_iHitsNumber;
            $data->win = $_bWin;
            $response = json_encode($data);
            $this->layout = 'ajax';
            $this->render(false);
            echo $response;
        } else {
            throw new Exception('Not found!', 404);
        }
    }

    public function bingoInit()
    {
        if ($this->Auth->user()) {
            $this->__initRedis();
            $userId = $this->Auth->user('id');
            $this->redis->del($userId.':bingo-grid');
            $this->redis->del($userId.':bingo-numbers');
            $this->redis->del($userId.':bingo-bet');
            $this->redis->del($userId.':bingo-balls');
            $this->redis->del($userId.':bingo-cards');
            $this->redis->del($userId.':bingo-paid');
            $settings = $this->GameSetting->getBingoSettings();
            $options['conditions'] = array('User.id' => $this->Auth->user('id'));
            $data = new \stdClass();
            $data->starting_money = floatval($this->User->find('first', $options)["User"]["balance"]);
            $data->time  = $settings['bingo_time_extraction']['value'];
            $data->paytable_45 = array_map('intval', explode(',', $settings['bingo_paytable_45']['value']));
            $data->paytable_55 = array_map('intval', explode(',', $settings['bingo_paytable_55']['value']));
            $data->paytable_65 = array_map('intval', explode(',', $settings['bingo_paytable_65']['value']));
            $data->fullscreen = intval($settings['bingo_fullscreen']['value']) == '1' ? true : false;
            $data->check_orientation = intval($settings['bingo_check_orientation']['value']) == '1' ? true : false;
            $response = json_encode($data);
            $this->layout = 'ajax';
            $this->render(false);
            echo $response;
        } else {
            throw new Exception('Not found!', 404);
        }
    }

    public function bingoBuy()
    {
        if ($this->Auth->user()) {
            if (!$this->request->data) {
                throw new Exception('Not found!', 404);
            }
            $userId = $this->Auth->user('id');
            $options['conditions'] = array('User.id' => $userId);
            $balance = floatval($this->User->find('first', $options)["User"]["balance"]);

            $request = json_decode($this->request->data);
            $bet = round(floatval($request->{'bet'}), 2);
            $balls = $request->{'balls'};
            $cards = $request->{'cards'};

            if (!in_array($balls, [45, 55, 65])) {
                throw new Exception('Not found!', 404);
            }

            if (!in_array($cards, [1, 2, 3, 4, 5, 6])) {
                throw new Exception('Not found!', 404);
            }

            if (!in_array($bet, [0.25, 0.50, 1.00])) {
                throw new Exception('Not found!', 404);
            }
            $totalBet = $bet * $cards;

            //Check if bet is higher than user balance or is 0
            if ($balance < $totalBet) {
                throw new Exception('Not found!', 404);
            }

            $grids = array();
            $numbers = array();
            for ($i=0; $i<$cards; $i++) {
                $gameVariables = $this->bingoGenerateCard();
                array_push($numbers, $gameVariables[0]);
                array_push($grids, $gameVariables[1]);
            }

            // log and sub from balance
            $this->User->addFunds($userId, (-1 * abs($totalBet)));
            $this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> bought cards for <b>".$totalBet."$</b> in <b>Bingo</b>", 'Bingo');

            // remember cards to redis
            $this->__initRedis();
            $this->redis->set($userId.':bingo-grid', serialize($grids));
            $this->redis->set($userId.':bingo-numbers', serialize($numbers));
            $this->redis->set($userId.':bingo-bet', $bet);
            $this->redis->set($userId.':bingo-balls', $balls);
            $this->redis->set($userId.':bingo-cards', $cards);
            $this->redis->set($userId.':bingo-paid', 1);

            $data = new \stdClass();
            $data->grids = $grids;
            $data->numbers = $numbers;
            $response = json_encode($data);
            $this->layout = 'ajax';
            $this->render(false);
            echo $response;
        } else {
            throw new Exception('Not found!', 404);
        }
    }

    public function bingoPlay()
    {
        if ($this->Auth->user()) {
            $userId = $this->Auth->user('id');
            $options['conditions'] = array('User.id' => $userId);
            $balance = floatval($this->User->find('first', $options)["User"]["balance"]);

            $this->__initRedis();
            if (!$this->redis->get($userId.':bingo-grid')) {
                throw new Exception('Not found!', 404);
            }

            $bet = $this->redis->get($userId.':bingo-bet');
            $cards = $this->redis->get($userId.':bingo-cards');
            $balls = $this->redis->get($userId.':bingo-balls');
            $totalBet = $bet * $cards;

            if (!($this->redis->get($userId.':bingo-paid') === '1')) {
                if ($balance < $totalBet) {
                    throw new Exception('Not found!', 404);
                }
                $this->User->addFunds($userId, (-1 * abs($totalBet)));
                $this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> bought cards for <b>".$totalBet."$</b> in <b>Bingo</b>", 'Bingo');
            }
            $this->redis->set($userId.':bingo-paid', 0);

            $settings = $this->GameSetting->getBingoSettings();
            $_iWinOccurrence = array_map('intval', explode(',', $settings['bingo_win_occurence']['value']));
            $paytable = null;
            if ($this->redis->get($userId.':bingo-balls') === '45') {
                $_iWinOccurrence = $_iWinOccurrence[0];
                $paytable = array_map('intval', explode(',', $settings['bingo_paytable_45']['value']));
            } else if ($this->redis->get($userId.':bingo-balls') === '55') {
                $_iWinOccurrence = $_iWinOccurrence[1];
                $paytable = array_map('intval', explode(',', $settings['bingo_paytable_55']['value']));
            } else if ($this->redis->get($userId.':bingo-balls') === '65') {
                $_iWinOccurrence = $_iWinOccurrence[2];
                $paytable = array_map('intval', explode(',', $settings['bingo_paytable_65']['value']));
            }

            $grid = unserialize($this->redis->get($userId.':bingo-grid'));
            $numbers = unserialize($this->redis->get($userId.':bingo-numbers'));
            for($z=0;$z<count($grid);$z++){
                for($i=0;$i<3;$i++) {
                    for ($j = 0; $j < 9; $j++) {
                        if ($grid[$z][$i][$j] === 'fill') {
                            $grid[$z][$i][$j] = $numbers[$z][$j][0];
                            array_splice($numbers[$z][$j], 0, 1);
                        } else {
                            $grid[$z][$i][$j] = 0;
                        }
                    }
                }
            }
            $_aCards = $grid;

            $aNumbers = array();
            $_aCurNumExtracted = array();
            $amount = $balls;

            //CHECK WIN OCCURRENCE
            $iRand = (float)rand()/(float)getrandmax()*100;
            if($iRand <= $_iWinOccurrence){
                //USER MUST WIN
                $iRandCard = floor((float)rand()/(float)getrandmax()*count($_aCards));
                $iRandIndex = floor((float)rand()/(float)getrandmax()*3);
                $aRow = $_aCards[$iRandCard][$iRandIndex];

                for($i=0;$i<count($aRow);$i++){
                    if ($aRow[$i] !== 0) {
                        array_push($_aCurNumExtracted, $aRow[$i]);
                    }
                }

                //GENERATE ALL NUMBERS
                for($k=0;$k<90;$k++){
                    $aNumbers[$k] = $k+1;
                }

                for($j=count($_aCurNumExtracted)-1;$j>=0;$j--){
                    array_splice($aNumbers, $_aCurNumExtracted[$j]-1, 1);
                }

                $amount = $balls - 5;
                for($i=0;$i<$amount;$i++){
                    $iRand = floor((float)rand()/(float)getrandmax()*count($aNumbers));
                    array_push($_aCurNumExtracted, $aNumbers[$iRand]);
                    array_splice($aNumbers, $iRand, 1);
                };
            }else{
                //USER MUST LOSE
                do{
                    //GENERATE ALL NUMBERS
                    $aNumbers = array();
                    for($k=0;$k<90;$k++){
                        $aNumbers[$k] = $k+1;
                    }

                    $_aCurNumExtracted = array();
                    for($i=0;$i<$amount;$i++){
                        $iRand = floor((float)rand()/(float)getrandmax()*count($aNumbers));
                        array_push($_aCurNumExtracted, $aNumbers[$iRand]);
                        array_splice($aNumbers, $iRand, 1);
                    };
                }while($this->checkBingoWin($_aCurNumExtracted, $_aCards) === true);
            }
            shuffle($_aCurNumExtracted);

            $_iTotWin = 0;
            for($i=0;$i<count($_aCards);$i++){
                $winningCols = 0;
                for($z=0;$z<3;$z++){
                    $iCont = 0;
                    for($j=0;$j<count($_aCards[$i][$z]);$j++){
                        for($k=0;$k<count($_aCurNumExtracted);$k++){
                            if($_aCurNumExtracted[$k] === $_aCards[$i][$z][$j]){
                                $iCont++;
                            }
                        }
                    }

                    if($iCont === 5){
                        $winningCols++;
                    }
                }
                if ($winningCols > 0) {
                    $_iTotWin += $bet*$paytable[$winningCols-1];
                }
            }

            if($_iTotWin > 0){
                $this->User->addFunds($userId, abs($_iTotWin));
                $this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> bet <b>".$totalBet."$</b> and won <b style='color: green'>".$_iTotWin."$</b> in <b>Bingo</b>", 'Bingo');
            } else {
                $this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> bet <b>".$totalBet."$</b> and lost <b style='color: red'>".$totalBet."$</b> in <b>Bingo</b>", 'Bingo');
            }

            $data = new \stdClass();
            $data->numbers = $_aCurNumExtracted;
            $response = json_encode($data);
            $this->layout = 'ajax';
            $this->render(false);
            echo $response;
        } else {
            throw new Exception('Not found!', 404);
        }
    }

    protected function bingoGenerateCard() {
        $aNumSeq = array();
        $iCurNum = 1;
        $_iRowHighlighted = 0;

        $iEnd = null;
        for ($i=0;$i<9;$i++){
            $aNumSeq[$i] = array();
            if($i === 0){
                $iEnd = 9;
            }else if($i === 8){
                $iEnd = 11;
            }else{
                $iEnd = 10;
            }
            for($j=0;$j<$iEnd;$j++){
                array_push($aNumSeq[$i], $iCurNum);
                $iCurNum++;
            }
        }

        //INIT LOGIC GRID
        $aColCounter = array();
        $_aGrid = array();
        for($i=0;$i<3;$i++){
            $_aGrid[$i] = array();
            for($j=0;$j<9;$j++){
                $_aGrid[$i][$j] = 'empty';
                $aColCounter[$j] = 0;
            }
        }

        //CHOOSE RANDOMICALLY THE CARD CELL TO FILL WITH NUMBERS
        for($i=0;$i<2;$i++){
            $aRandCols = [0,1,2,3,4,5,6,7,8];
            for($j=0;$j<5;$j++){
                $iRandIndex = floor((float)rand()/(float)getrandmax()*count($aRandCols));
                $_aGrid[$i][$aRandCols[$iRandIndex]] = 'fill';
                $aColCounter[$aRandCols[$iRandIndex]] +=1;
                array_splice($aRandCols, $iRandIndex, 1);
            }
        }

        //CHECK IF SOME COLUMNS HAVE NO FILLINGS. IF SO FILL THEM
        $aRandCols = array();
        $iCont = 5;
        for($k=0;$k<9;$k++){
            if($aColCounter[$k] === 0){
                $_aGrid[$i][$k] = 'fill';
                $aColCounter[$k] +=1;
                $iCont--;
            }else{
                array_push($aRandCols, $k);
            }
        }

        for($t=0;$t<$iCont;$t++){
            $iRandIndex = floor((float)rand()/(float)getrandmax()*count($aRandCols));
            $_aGrid[$i][$aRandCols[$iRandIndex]] = 'fill';
            $aColCounter[$aRandCols[$iRandIndex]] +=1;
            array_splice($aRandCols, $iRandIndex, 1);
        }

        //CHOOSE RANDOMICALLY NUMBERS INTO THE CARD
        $aTmpNum = array();
        for($k=0;$k<9;$k++){
            $aTmpNum[$k] = array();
            for($t=0;$t<$aColCounter[$k];$t++){
                $iRand = floor((float)rand()/(float)getrandmax()*count($aNumSeq[$k]));
                array_push($aTmpNum[$k], $aNumSeq[$k][$iRand]);
                array_splice($aNumSeq[$k], $iRand, 1);
            }
            $aTmpNum[$k] = $this->bubble_sort($aTmpNum[$k]);
        }

        return [$aTmpNum, $_aGrid];
    }

    protected function checkBingoWin($numbers, $cards) {
        $bWin = false;
        for($i=0;$i<count($cards);$i++){
            $aWinningRow = array();
            for($z=0;$z<3;$z++){
                $iCont = 0;
                for($j=0;$j<count($cards[$i][$z]);$j++){
                    for($k=0;$k<count($numbers);$k++){
                        if($numbers[$k] === $cards[$i][$z][$j]){
                            $iCont++;
                        }
                    }
                }

                if($iCont === 5){
                    array_push($aWinningRow, $i);
                }
            }
            if(count($aWinningRow) > 0){
                $bWin = true;
            }
        }
        return $bWin;
    }

    protected function bubble_sort($arr) {
        $size = count($arr)-1;
        for ($i=0; $i<$size; $i++) {
            for ($j=0; $j<$size-$i; $j++) {
                $k = $j+1;
                if ($arr[$k] < $arr[$j]) {
                    // Swap elements at indices: $j, $k
                    list($arr[$j], $arr[$k]) = array($arr[$k], $arr[$j]);
                }
            }
        }
        return $arr;
    }

    public function greyhoundInit()
    {
        if ($this->Auth->user()) {
            $settings = $this->GameSetting->getGreyhoundSettings();
            $options['conditions'] = array('User.id' => $this->Auth->user('id'));
            $data = new \stdClass();
            $data->starting_money = floatval($this->User->find('first', $options)["User"]["balance"]);

            for($i=1; $i<7; $i++) {
                $odds = $settings['greyhound_odd_'.$i]['value'];
                $odds = array_map('floatval', explode(',', $odds));
                $name = 'odds_'.$i;
                $data->{$name} = $odds;
            }

            $data->names = array_map('strval', explode(',', $settings['greyhound_names']['value']));
            $data->odd_win = array_map('floatval', explode(',', $settings['greyhound_odd_win']['value']));
            $data->odd_place = array_map('floatval', explode(',', $settings['greyhound_odd_place']['value']));
            $data->odd_show = array_map('floatval', explode(',', $settings['greyhound_odd_show']['value']));

            $data->min_bet  = floatval($settings['greyhound_min_bet']['value']);
            $data->max_bet  = floatval($settings['greyhound_max_bet']['value']);
            $data->fullscreen = intval($settings['greyhound_fullscreen']['value']) == '1' ? true : false;
            $data->check_orientation = intval($settings['greyhound_check_orientation']['value']) == '1' ? true : false;
            $data->show_credits = intval($settings['greyhound_show_credits']['value']) == '1' ? true : false;
            $response = json_encode($data);
            $this->layout = 'ajax';
            $this->render(false);
            echo $response;
        } else {
            throw new Exception('Not found!', 404);
        }
    }

    public function horseInit()
    {
        if ($this->Auth->user()) {
            $settings = $this->GameSetting->getHorseSettings();
            $options['conditions'] = array('User.id' => $this->Auth->user('id'));
            $data = new \stdClass();
            $data->starting_money = floatval($this->User->find('first', $options)["User"]["balance"]);

            for($i=1; $i<9; $i++) {
                $odds = $settings['horse_odd_'.$i]['value'];
                $odds = array_map('floatval', explode(',', $odds));
                $name = 'odds_'.$i;
                $data->{$name} = $odds;
            }

            $data->names = array_map('strval', explode(',', $settings['horse_names']['value']));
            $data->odd_win = array_map('floatval', explode(',', $settings['horse_odd_win']['value']));
            $data->odd_place = array_map('floatval', explode(',', $settings['horse_odd_place']['value']));
            $data->odd_show = array_map('floatval', explode(',', $settings['horse_odd_show']['value']));

            $data->min_bet  = floatval($settings['horse_min_bet']['value']);
            $data->max_bet  = floatval($settings['horse_max_bet']['value']);
            $data->fullscreen = intval($settings['horse_fullscreen']['value']) == '1' ? true : false;
            $data->check_orientation = intval($settings['horse_check_orientation']['value']) == '1' ? true : false;
            $data->show_credits = intval($settings['horse_show_credits']['value']) == '1' ? true : false;
            $response = json_encode($data);
            $this->layout = 'ajax';
            $this->render(false);
            echo $response;
        } else {
            throw new Exception('Not found!', 404);
        }
    }

    public function greyhoundPlay()
    {
        if ($this->Auth->user()) {
            if (!$this->request->data) {
                throw new Exception('Not found!', 404);
            }
            $userId = $this->Auth->user('id');
            $options['conditions'] = array('User.id' => $userId);
            $balance = floatval($this->User->find('first', $options)["User"]["balance"]);

            $request = json_decode($this->request->data);
            $data = $request->{'data'};
            $totalBet = 0;
            $winCombo = array();
            $placeCombo = array();
            $showCombo = array();
            $multiCombo = array();
            $settings = $this->GameSetting->getGreyhoundSettings();
            $winOdds = array_map('floatval', explode(',', $settings['greyhound_odd_win']['value']));
            $placeOdds = array_map('floatval', explode(',', $settings['greyhound_odd_place']['value']));
            $showOdds = array_map('floatval', explode(',', $settings['greyhound_odd_show']['value']));
            $multiOdds = array();
            for($i=1; $i<7; $i++) {
                $odds = $settings['greyhound_odd_'.$i]['value'];
                $odds = array_map('floatval', explode(',', $odds));
                $tempOdds = array();
                $counter = 0;
                for($j=0; $j<6; $j++) {
                    if ($j === $i-1) {
                        array_push($tempOdds, 0);
                    } else {
                        array_push($tempOdds, $odds[$counter]);
                        $counter++;
                    }
                }
                $multiOdds[$i-1] = $tempOdds;
            }
            foreach($data as $bet) {
                if ($bet->type_bet === 'simple') {
                    if (in_array($bet->greyhounds[0]->index, [0,1,2,3,4,5])) {
                        if (in_array($bet->greyhounds[0]->place, [1,2,3])) {
                            if ($bet->greyhounds[0]->place === 1) {
                                array_push($winCombo, [$bet->greyhounds[0]->index, $winOdds[$bet->greyhounds[0]->index], $bet->bet]);
                            } else if ($bet->greyhounds[0]->place === 2) {
                                array_push($placeCombo, [$bet->greyhounds[0]->index, $placeOdds[$bet->greyhounds[0]->index], $bet->bet]);
                            } else if ($bet->greyhounds[0]->place === 3) {
                                array_push($showCombo, [$bet->greyhounds[0]->index, $showOdds[$bet->greyhounds[0]->index], $bet->bet]);
                            }
                            $totalBet += $bet->bet;
                        }
                    }
                } else if ($bet->type_bet === 'forecast') {
                    if ($bet->greyhounds[0]->index === $bet->greyhounds[1]->index) {
                        return;
                    }
                    if (!in_array($bet->greyhounds[0]->index, [0,1,2,3,4,5])) {
                        return;
                    }
                    if (!in_array($bet->greyhounds[1]->index, [0,1,2,3,4,5])) {
                        return;
                    }
                    if ($bet->greyhounds[0]->place !== 1 && $bet->greyhounds[1]->place !== 2) {
                        return;
                    }
                    $winnings = $multiOdds[$bet->greyhounds[0]->index][$bet->greyhounds[1]->index];
                    array_push($multiCombo, [[$bet->greyhounds[0]->index, $bet->greyhounds[1]->index], $winnings, $bet->bet]);
                    $totalBet += $bet->bet;
                }
            }

            //Check if bet is higher than user balance or is 0
            if ($balance < $totalBet || $totalBet === 0) {
                throw new Exception('Not found!', 404);
            }

            // check win factor
            $winOccurence  = floatval($settings['greyhound_win_occurence']['value']);
            $iRand = (float)rand()/(float)getrandmax()*100;
            $result = null;
            $winnings = null;
            if($iRand <= $winOccurence){
                //generate random selection array
                $randomSample = $this->generateRandomSample($winOdds);
                //get run array
                $result = $this->getRunResult($randomSample, 6);
                //calculateWin
                $winnings = $this->getRunWinnings($result, $winCombo, $placeCombo, $showCombo, $multiCombo);
            } else {
                $tries = 0;
                do {
                    //generate random selection array
                    $randomSample = $this->generateRandomSample($winOdds);
                    //get run array
                    $result = $this->getRunResult($randomSample, 6);
                    //calculateWin
                    $winnings = $this->getRunWinnings($result, $winCombo, $placeCombo, $showCombo, $multiCombo);
                    $tries++;
                    if ($tries > 20) {
                        break;
                    }
                } while($winnings !== 0);
            }

            $this->User->addFunds($userId, (-1 * abs($totalBet)));
            if($winnings > 0){
                $this->User->addFunds($userId, abs($winnings));
                $this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> bet <b>".$totalBet."$</b> and won <b style='color: green'>".$winnings."$</b> in <b>Greyhound racing</b>", 'Greyhound racing');
            } else {
                $this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> bet <b>".$totalBet."$</b> and lost <b style='color: red'>".$totalBet."$</b> in <b>Greyhound racing</b>", 'Greyhound racing');
            }

            $data = new \stdClass();
            $data->result = $result;
            $response = json_encode($data);
            $this->layout = 'ajax';
            $this->render(false);
            echo $response;
        } else {
            throw new Exception('Not found!', 404);
        }
    }

    public function horsePlay()
    {
        if ($this->Auth->user()) {
            if (!$this->request->data) {
                throw new Exception('Not found!', 404);
            }
            $userId = $this->Auth->user('id');
            $options['conditions'] = array('User.id' => $userId);
            $balance = floatval($this->User->find('first', $options)["User"]["balance"]);

            $request = json_decode($this->request->data);
            $data = $request->{'data'};
            $totalBet = 0;
            $winCombo = array();
            $placeCombo = array();
            $showCombo = array();
            $multiCombo = array();
            $settings = $this->GameSetting->getHorseSettings();
            $winOdds = array_map('floatval', explode(',', $settings['horse_odd_win']['value']));
            $placeOdds = array_map('floatval', explode(',', $settings['horse_odd_place']['value']));
            $showOdds = array_map('floatval', explode(',', $settings['horse_odd_show']['value']));
            $multiOdds = array();
            for($i=1; $i<9; $i++) {
                $odds = $settings['horse_odd_'.$i]['value'];
                $odds = array_map('floatval', explode(',', $odds));
                $tempOdds = array();
                $counter = 0;
                for($j=0; $j<8; $j++) {
                    if ($j === $i-1) {
                        array_push($tempOdds, 0);
                    } else {
                        array_push($tempOdds, $odds[$counter]);
                        $counter++;
                    }
                }
                $multiOdds[$i-1] = $tempOdds;
            }
            foreach($data as $bet) {
                if ($bet->type_bet === 'simple') {
                    if (in_array($bet->horses[0]->index, [0,1,2,3,4,5,6,7])) {
                        if (in_array($bet->horses[0]->place, [1,2,3])) {
                            if ($bet->horses[0]->place === 1) {
                                array_push($winCombo, [$bet->horses[0]->index, $winOdds[$bet->horses[0]->index], $bet->bet]);
                            } else if ($bet->horses[0]->place === 2) {
                                array_push($placeCombo, [$bet->horses[0]->index, $placeOdds[$bet->horses[0]->index], $bet->bet]);
                            } else if ($bet->horses[0]->place === 3) {
                                array_push($showCombo, [$bet->horses[0]->index, $showOdds[$bet->horses[0]->index], $bet->bet]);
                            }
                            $totalBet += $bet->bet;
                        }
                    }
                } else if ($bet->type_bet === 'forecast') {
                    if ($bet->horses[0]->index === $bet->horses[1]->index) {
                        return;
                    }
                    if (!in_array($bet->horses[0]->index, [0,1,2,3,4,5,6,7])) {
                        return;
                    }
                    if (!in_array($bet->horses[1]->index, [0,1,2,3,4,5,6,7])) {
                        return;
                    }
                    if ($bet->horses[0]->place !== 1 && $bet->horses[1]->place !== 2) {
                        return;
                    }
                    $winnings = $multiOdds[$bet->horses[0]->index][$bet->horses[1]->index];
                    array_push($multiCombo, [[$bet->horses[0]->index, $bet->horses[1]->index], $winnings, $bet->bet]);
                    $totalBet += $bet->bet;
                }
            }

            //Check if bet is higher than user balance or is 0
            if ($balance < $totalBet || $totalBet === 0) {
                throw new Exception('Not found!', 404);
            }

            // check win factor
            $winOccurence  = floatval($settings['horse_win_occurence']['value']);
            $iRand = (float)rand()/(float)getrandmax()*100;
            $result = null;
            $winnings = null;
            if($iRand <= $winOccurence){
                //generate random selection array
                $randomSample = $this->generateRandomSample($winOdds);
                //get run array
                $result = $this->getRunResult($randomSample, 8);
                //calculateWin
                $winnings = $this->getRunWinnings($result, $winCombo, $placeCombo, $showCombo, $multiCombo);
            } else {
                $tries = 0;
                do {
                    //generate random selection array
                    $randomSample = $this->generateRandomSample($winOdds);
                    //get run array
                    $result = $this->getRunResult($randomSample, 8);
                    //calculateWin
                    $winnings = $this->getRunWinnings($result, $winCombo, $placeCombo, $showCombo, $multiCombo);
                    $tries++;
                    if ($tries > 20) {
                        break;
                    }
                } while($winnings !== 0);
            }

            $this->User->addFunds($userId, (-1 * abs($totalBet)));
            if($winnings > 0){
                $this->User->addFunds($userId, abs($winnings));
                $this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> bet <b>".$totalBet."$</b> and won <b style='color: green'>".$winnings."$</b> in <b>Horse racing</b>", 'Horse racing');
            } else {
                $this->GameLog->write($userId, "<b>".$this->Auth->user('username')."</b> bet <b>".$totalBet."$</b> and lost <b style='color: red'>".$totalBet."$</b> in <b>Horse racing</b>", 'Horse racing');
            }

            $data = new \stdClass();
            $data->result = $result;
            $response = json_encode($data);
            $this->layout = 'ajax';
            $this->render(false);
            echo $response;
        } else {
            throw new Exception('Not found!', 404);
        }
    }

    protected function generateRandomSample($arr) {
        $randomSample = array();
        foreach ($arr as $key => $value) {
            $count = floor(1000/$value);
            while ($count <> 0) {
                array_push($randomSample, $key);
                $count--;
            }
        }
        shuffle($randomSample);

        return $randomSample;
    }

    protected function getRunResult($sample, $count) {
	    $result = array();
	    for ($i=0; $i<$count; $i++) {
            $randomIndex = floor((float)rand()/(float)getrandmax()*count($sample));
            $number = $sample[$randomIndex];
            array_push($result, $number);
            for ($j=0;$j<count($sample);$j++) {
                if ($sample[$j] === $number) {
                    array_splice($sample, $j, 1);
                    $j--;
                }
            }
        }

        return $result;
    }

    protected function getRunWinnings($result, $winCombo, $placeCombo, $showCombo, $multiCombo) {
        $totalWin = 0;

        foreach ($winCombo as $combo) {
            if ($result[0] === $combo[0]) {
                $totalWin += floatval($combo[1]) * floatval($combo[2]);
            }
        }

        foreach ($placeCombo as $combo) {
            if ($result[0] === $combo[0] || $result[1] === $combo[0]) {
                $totalWin += floatval($combo[1]) * floatval($combo[2]);
            }
        }

        foreach ($showCombo as $combo) {
            if ($result[0] === $combo[0] || $result[1] === $combo[0] || $result[2] === $combo[0]) {
                $totalWin += floatval($combo[1]) * floatval($combo[2]);
            }
        }

        foreach ($multiCombo as $combo) {
            if ($result[0] === $combo[0][0] && $result[1] === $combo[0][1]) {
                $totalWin += floatval($combo[1]) * floatval($combo[2]);
            }
        }

        return $totalWin;
    }
}