<?php

namespace App\Repositories;

use App\Models\Call;
use App\Repositories\Contracts\CallRepositoryInterface;

use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

use Twilio\Jwt\AccessToken;
use Twilio\TwiML\VoiceResponse;
use Twilio\Jwt\Grants\VoiceGrant;

class CallRepository implements CallRepositoryInterface
{
    protected $model;

    public function __construct(Call $model)
    {
        $this->model = $model;
    }

    public function getAll(array $params)
    {
        $response = QueryBuilder::For(Call::class)->allowedFilters([AllowedFilter::exact('id'),
                                                                    AllowedFilter::exact('call_sid'),
                                                                    AllowedFilter::exact('status')])
                                                    ->defaultSort('-created_at')
                                                    ->allowedSorts('created_at', '-created_at')
                                                    ->forPage($params['page'], $params['per_page'])
                                                    ->paginate($params['per_page'])
                                                    ->appends(request()->query());
        return $response;
    }

    public function create(array $params)
    {
        return $this->model->create($params);
    }

    public function generateToken()
    {
        $token = new AccessToken(
            $_ENV['TWILIO_ACCOUNT_SID'],
            $_ENV['TWILIO_API_KEY'],
            $_ENV['TWILIO_API_SECRET'],
            36000,
        );
        $voiceGrant = new VoiceGrant();
        $voiceGrant->setOutgoingApplicationSid($_ENV['TWILIO_TWIML_APP_SID']);
        $voiceGrant->setIncomingAllow(true);
        $token->addGrant($voiceGrant);
        $token = $token->toJWT();
        return $token;
    }

    public function createVoice(string $toNumber)
    {
        $response = new VoiceResponse();
        $dial = $response->dial(null, ['callerId' => $_ENV['TWILIO_PHONE_NUMBER']]);

        if (isset($toNumber)) {
            $dial->number($toNumber);
        } else {
            $dial->client('support_agent');
        }

        return $response;
    }

    public function getCallBySid(string $sid)
    {
        return $this->model->where('call_sid', $sid)->first();
    }

    public function update(object $call, array $params)
    {
        return $call->update($params);
    }
}
