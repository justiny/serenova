import { isMobile, isInViewport} from './utilities';
import * as Plyr from "plyr";

const plyrOptions = {
  autoplay: false,
  controls: ["play", "volume", "mute"]
};

const mobileCheck = isMobile();

const players = Plyr.setup(".plyr-video-embed");

if (players) {
  $(window).scroll(function () {
    players.forEach((player) => {
      let $player = $(player.elements.container)[0];
      if (!mobileCheck) {
        if (isInViewport($player)) {
          player.pause();
        } else {
          player.pause();
        }
      }
    });
  });
}

