<?php

namespace Telegram\Bot\Laravel\Facades;

use Telegram\Bot\BotsManager;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Telegram\Bot\BotsManager manager($config)
 * @method static macro($name, $macro)
 * @method static mixin($mixin, $replace = true)
 * @method static hasMacro($name)
 * @method static macroCall($method, $parameters)
 * @method static \League\Event\Emitter getEventEmitter()
 * @method static setEventEmitter($eventEmitter)
 * @method static setHttpClientHandler(\Telegram\Bot\HttpClients\HttpClientInterface $httpClientHandler)
 * @method static getLastResponse()
 * @method static string getAccessToken()
 * @method static setAccessToken(string $accessToken)
 * @method static bool isAsyncRequest()
 * @method static setAsyncRequest(bool $isAsyncRequest)
 * @method static int getTimeOut()
 * @method static setTimeOut(int $timeOut)
 * @method static int getConnectTimeOut()
 * @method static setConnectTimeOut(int $connectTimeOut)
 * @method static array getCommands()
 * @method static commandsHandler(bool $webhook = false)
 * @method static processCommand(\Telegram\Bot\Objects\Update $update)
 * @method static triggerCommand(string $name, \Telegram\Bot\Objects\Update $update, $entity = null)
 * @method static setContainer(\Illuminate\Contracts\Container\Container $container)
 * @method static \Illuminate\Contracts\Container\Container getContainer()
 * @method static bool hasContainer()
 * @method static bool kickChatMember(array $params)
 * @method static string exportChatInviteLink(array $params)
 * @method static bool setChatPhoto(array $params)
 * @method static bool deleteChatPhoto(array $params)
 * @method static bool setChatTitle(array $params)
 * @method static bool setChatDescription(array $params)
 * @method static bool pinChatMessage(array $params)
 * @method static bool unpinChatMessage(array $params)
 * @method static bool leaveChat(array $params)
 * @method static bool unbanChatMember(array $params)
 * @method static bool restrictChatMember(array $params)
 * @method static bool promoteChatMember(array $params)
 * @method static bool setChatAdministratorCustomTitle(array $params)
 * @method static bool setChatPermissions(array $params)
 * @method static \Telegram\Bot\Objects\Chat getChat(array $params)
 * @method static array getChatAdministrators(array $params)
 * @method static int getChatMembersCount(array $params)
 * @method static \Telegram\Bot\Objects\ChatMember getChatMember(array $params)
 * @method static bool setChatStickerSet(array $params)
 * @method static bool deleteChatStickerSet(array $params)
 * @method static array getMyCommands()
 * @method static bool setMyCommands(array $params)
 * @method static editMessageText(array $params)
 * @method static editMessageCaption(array $params)
 * @method static editMessageMedia(array $params)
 * @method static editMessageReplyMarkup(array $params)
 * @method static stopPoll(array $params)
 * @method static deleteMessage(array $params)
 * @method static \Telegram\Bot\Objects\Message sendGame(array $params)
 * @method static \Telegram\Bot\Objects\Message setGameScore(array $params)
 * @method static array getGameHighScores(array $params)
 * @method static \Telegram\Bot\Objects\User getMe()
 * @method static \Telegram\Bot\Objects\UserProfilePhotos getUserProfilePhotos(array $params)
 * @method static \Telegram\Bot\Objects\File getFile(array $params)
 * @method static \Telegram\Bot\Objects\Message sendLocation(array $params)
 * @method static editMessageLiveLocation(array $params)
 * @method static stopMessageLiveLocation(array $params)
 * @method static \Telegram\Bot\Objects\Message sendMessage(array $params)
 * @method static int copyMessage(array $params)
 * @method static \Telegram\Bot\Objects\Message forwardMessage(array $params)
 * @method static \Telegram\Bot\Objects\Message sendPhoto(array $params)
 * @method static \Telegram\Bot\Objects\Message sendAudio(array $params)
 * @method static \Telegram\Bot\Objects\Message sendDocument(array $params)
 * @method static \Telegram\Bot\Objects\Message sendVideo(array $params)
 * @method static \Telegram\Bot\Objects\Message sendAnimation(array $params)
 * @method static \Telegram\Bot\Objects\Message sendVoice(array $params)
 * @method static \Telegram\Bot\Objects\Message sendVideoNote(array $params)
 * @method static sendMediaGroup(array $params)
 * @method static \Telegram\Bot\Objects\Message sendVenue(array $params)
 * @method static \Telegram\Bot\Objects\Message sendContact(array $params)
 * @method static \Telegram\Bot\Objects\Message sendPoll(array $params)
 * @method static \Telegram\Bot\Objects\Message sendDice(array $params)
 * @method static bool sendChatAction(array $params)
 * @method static setPassportDataErrors(array $params)
 * @method static \Telegram\Bot\Objects\Message sendInvoice(array $params)
 * @method static bool answerShippingQuery(array $params)
 * @method static bool answerPreCheckoutQuery(array $params)
 * @method static bool answerCallbackQuery(array $params)
 * @method static bool answerInlineQuery(array $params)
 * @method static \Telegram\Bot\Objects\Message sendSticker(array $params)
 * @method static \Telegram\Bot\Objects\StickerSet getStickerSet(array $params)
 * @method static \Telegram\Bot\Objects\File uploadStickerFile(array $params)
 * @method static bool createNewStickerSet(array $params)
 * @method static bool addStickerToSet(array $params)
 * @method static bool setStickerPositionInSet(array $params)
 * @method static bool deleteStickerFromSet(array $params)
 * @method static bool setStickerSetThumb(array $params)
 * @method static bool setWebhook(array $params)
 * @method static bool deleteWebhook()
 * @method static \Telegram\Bot\Objects\WebhookInfo getWebhookInfo()
 * @method static \Telegram\Bot\Objects\Update getWebhookUpdates($shouldEmitEvent = true)
 * @method static \Telegram\Bot\Objects\Update getWebhookUpdate($shouldEmitEvent = true)
 * @method static bool removeWebhook()
 * @method static array getBotConfig($name = null)
 * @method static \Telegram\Bot\Api bot($name = null)
 * @method static \Telegram\Bot\Api reconnect($name = null)
 * @method static self disconnect($name = null)
 * @method static getConfig($key, $default = null)
 * @method static getDefaultBotName()
 * @method static self setDefaultBot($name)
 * @method static array getBots()
 * @method static void ignoreUpdateWhen(callable $callable)
 * @method static array parseBotCommands(array $commands)
 * @method static \Telegram\Bot\Objects\Update[] getUpdates(array $params = [], $shouldEmitEvents = true)
 *
 * @see \Telegram\Bot\Api
 * @see \Telegram\Bot\BotsManager
 */
class Telegram extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return BotsManager::class;
    }
}
