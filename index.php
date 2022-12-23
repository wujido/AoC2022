<?php

use Util\ContentLoader;

require_once("Util/ContentLoader.php");

$contentLoader = new ContentLoader();
$days = $contentLoader->getAvailableDays();
$activeDay = $contentLoader->getActiveDay();
?>
<!doctype html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Advent of Code 2022</title>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>

<p class="is-size-7">
    <?= date('Y-m-d H:i:m (e)') ?>
</p>
<div class="container p-4">
   <div class="box">
      <h1 class="title">Advent of Code 2022</h1>
      <p><em>Advent of Code</em> is an <a href="https://en.wikipedia.org/wiki/Advent_calendar">Advent calendar</a> of small programming puzzles for a variety of skill sets and skill levels that can be
         solved in <a href="https://github.com/search?q=advent+of+code">any</a> programming language you like.</p>
   </div>
   <div class="notification is-danger is-hidden" id="error-box">
      <button class="delete" onclick="hideError()"></button>
      <p id="error-msg">Error</p>
   </div>
   <div class="columns">
      <div class="column is-three-quarters">
         <div class="box px-0">
            <aside class="tabs is-boxed is-small">
               <ul>
                   <?php for ($i = 1; $i <= $days; $i++) { ?>
                      <li
                          <?php if ($i == $activeDay) { ?>class="is-active" <?php } ?>
                          id="tab-<?= $i ?>"
                          data-day="<?= $i ?>"
                      >
                         <a onclick="openDay(<?= $i ?>)"><?= $i ?></a>
                      </li>
                   <?php } ?>
               </ul>
            </aside>

            <div class="day-description px-4">
                <?php for ($i = 1; $i <= $days; $i++) { ?>
                   <section class="content <?php if ($i != $activeDay) { ?>is-hidden<?php } ?>" id="day-<?= $i ?>">
                       <?php
                       if ($i == $activeDay) {
                           echo $contentLoader->loadTask($i);
                       }
                       ?>
                   </section>
                <?php } ?>
            </div>
         </div>
      </div>
      <div class="column is-one-quarter">
         <div style="position: sticky; top: 1rem">
            <div class="box p-0">
               <div class="py-4 is-flex is-justify-content-center field is-grouped">
                  <p class="control">
                     <button class="button is-small is-success" onclick="runSolution()">Run Solution</button>
                  </p>
                  <p class="control">
                     <button class="button is-small is-warning" onclick="runSolution(true)">Run Test</button>
                  </p>
               </div>
               <table class="table is-fullwidth">
                  <thead>
                  <tr>
                     <th>Part</th>
                     <th>Result</th>
                     <th>Time</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                     <td class="has-text-weight-bold">1.</td>
                     <td id="part-1">Did not</td>
                     <td id="part-1-time" style="font-size: 10px"></td>
                  </tr>
                  <tr>
                     <td class="has-text-weight-bold">2.</td>
                     <td id="part-2">run</td>
                     <td id="part-2-time" style="font-size: 10px"></td>
                  </tr>
                  </tbody>
               </table>
            </div>
            <div class="box">
               <label for="answer" class="label">Did you submit answer?</label>
               <div class="field has-addons">
                  <div class="control">
                     <button type="submit" class="button is-info" onclick="refreshTask()">
                        Load next part
                     </button>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<script>
   const results = new Array(<?= $days ?>).fill([
      {'result': 'Did not', 'execTime': ''},
      {'result': 'run', 'execTime': ''}
   ])

   async function loadTask(section, day) {
      section.innerHTML = '<p>Loading...</p>';
      const res = await fetch(`load.php?day=${day}`);
      section.innerHTML = await res.text();
   }

   async function openDay(day) {
      const activeSection = document.querySelector('.day-description section:not(.is-hidden)');
      activeSection.classList.toggle('is-hidden');

      const activeTab = document.querySelector('li.is-active');
      activeTab.classList.toggle('is-active');

      const newSection = document.querySelector(`#day-${day}`);
      newSection.classList.toggle('is-hidden');

      if (newSection.innerHTML.trim().length === 0) {
         await loadTask(newSection, day);
      }

      const newTab = document.querySelector(`#tab-${day}`);
      newTab.classList.toggle('is-active');

      updateResults(day)
   }

   function updateResults(day) {
      const createButton = part => `<button class="button is-small is-info" onclick="createClass(${day}, ${part})">Create Class</button>`;
      const isNotImplemented = res => res === "Class dont exists";

      const firstResult = results[day - 1][0]['result'];
      const secondResult = results[day - 1][1]['result'];

      document.querySelector('#part-1').innerHTML = isNotImplemented(firstResult) ? createButton(1) : firstResult
      document.querySelector('#part-2').innerHTML = isNotImplemented(secondResult) ? createButton(2) : secondResult

      document.querySelector('#part-1-time').innerHTML = results[day - 1][0]['execTime'];
      document.querySelector('#part-2-time').innerHTML = results[day - 1][1]['execTime'];
   }

   function getActiveDay() {
      return document.querySelector('.tabs li.is-active').dataset.day;
   }

   function runSolution(test = false) {
      hideError();
      const activeDay = getActiveDay();

      results[activeDay - 1] = [
         {'result': 'Loading...', 'execTime': ''},
         {'result': 'Loading...', 'execTime': ''}
      ];
      updateResults(activeDay)

      let url = `run.php?day=${activeDay}`;

      if (test)
         url += '&test=1';

      fetch(url)
         .then(async (r) => {
            try {
               return await r.json()
            } catch (e) {
               showError(e);
               return [{'result': "Error", 'execTime': ''}, {'result': "Occurred", 'execTime': ''}];
            }
         })
         .then(res => {
            if (!Array.isArray(res)) {
               showError(res);
               res = [{'result': "Error", 'execTime': ''}, {'result': "Occurred", 'execTime': ''}];
            }

            results[activeDay - 1] = res
            updateResults(activeDay);
         })
         .catch(err => {
            showError(err)
         })
   }

   function hideError() {
      const box = document.querySelector('#error-box');
      box.classList.add('is-hidden');
   }

   function showError(err) {
      const errMsg = document.querySelector('#error-msg');
      errMsg.innerHTML = err;

      const box = document.querySelector('#error-box');
      box.classList.remove('is-hidden');
   }

   function createClass(day, part) {
      fetch(`create.php?day=${day}&part=${part}`)
         .then(async r => {
            const res = await r.text();


            results[day - 1][part - 1]['result'] = res === '1'
               ? 'Not Implemented'
               : 'Creation failed';

            updateResults(day);
         });
   }

   function refreshTask() {
      const activeSection = document.querySelector('.day-description section:not(.is-hidden)');
      const activeDay = getActiveDay();

      loadTask(activeSection, activeDay);
   }
</script>
</body>
</html>
